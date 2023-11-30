<?php
session_start();
require_once("../sett.php");
require_once("../conf/env_conf.php");
require_once("db_open.php");

require_once("../classes/sec.php");
$sec = new sec();
require_once("../classes/basic.php");
$basic = new basic();

$get_params = $sec->allowed_get_params(array('action_id'));
$g_action_id = $get_params["action_id"];

if (trim($g_action_id) == "") {
  $post_params = $sec->allowed_post_params(array('action_id'));
  $g_action_id = $post_params["action_id"];
}

switch ($g_action_id) {
    /* #region  "login" */
  case "login":
    $post_form_status = $sec->checkPostForm();
    //$post_form_status = 1;
    if ($post_form_status == 1) {
      $post_params = $sec->allowed_post_params(array('user', 'pwd'));
      $user = $post_params['user'];
      $pwd = $post_params['pwd'];

      $sql = "SELECT * FROM " . TBL_USER . " WHERE email = :email AND enabled = :enabled LIMIT 1";
      $stmt = $db_pdo->prepare($sql);
      $stmt->bindValue(':email', $user, PDO::PARAM_STR);
      $stmt->bindValue(':enabled', 1, PDO::PARAM_INT);
      $stmt->execute();
      if ($stmt->rowCount() > 0) {
        $record_user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (password_verify($pwd, $record_user["pwd"])) {
          $ts = date("Y-m-d H:i:s");
          $_SESSION["admin"]["auth"]["status"] = true; // uspesne nalogovany uzivatel
          $_SESSION["admin"]["auth"]["sess_time"] = time();
          $_SESSION["admin"]["auth"]["email"] = $record_user["email"];
          $_SESSION["admin"]["auth"]["id_user"] = $record_user["id"];
          $_SESSION["admin"]["auth"]["id_type"] = $record_user["id_type"];
          $_SESSION["admin"]["auth"]["max_idle_time"] = MAX_INACTIVITY;


          $sql = "UPDATE " . TBL_USER . " SET ts_last_login = :ts_last_login WHERE id = :id";
          $stmt = $db_pdo->prepare($sql);
          $stmt->bindValue(':ts_last_login', $ts, PDO::PARAM_STR);
          $stmt->bindValue(':id', $_SESSION["admin"]["auth"]["id_user"], PDO::PARAM_INT);
          $stmt->execute();

          HEADER("Location: " . DIR_WWW_ROOT . "app/index.html");
          exit();
        } else {
          $_SESSION["admin"]["auth"]["status"] = false;

          $_SESSION["admin"]["msg"]["status"] = true;
          $_SESSION["admin"]["msg"]["nadpis"] = 'Prihlásenie';
          $_SESSION["admin"]["msg"]["popis"] = "Neplatné užívateľské meno alebo heslo";
          $_SESSION["admin"]["msg"]["scope"] = 'login';
          $_SESSION["admin"]["msg"]["back_url"] = DIR_WWW_ROOT . 'app/index.html';
          HEADER("Location: " . DIR_WWW_ROOT . "msg/i/error/login.html");
          exit();
        }
      } else {
        $_SESSION["admin"]["auth"]["status"] = false;

        $_SESSION["admin"]["msg"]["status"] = true;
        $_SESSION["admin"]["msg"]["nadpis"] = 'Prihlásenie';
        $_SESSION["admin"]["msg"]["popis"] = "Neplatné užívateľské meno alebo heslo";
        $_SESSION["admin"]["msg"]["scope"] = 'login';
        $_SESSION["admin"]["msg"]["back_url"] = DIR_WWW_ROOT . 'app/index.html';
        HEADER("Location: " . DIR_WWW_ROOT . "msg/i/error/login.html");
        exit();
      }
    } else {
      $_SESSION["admin"]["auth"]["status"] = false;

      $_SESSION["admin"]["msg"]["status"] = true;
      $_SESSION["admin"]["msg"]["nadpis"] = 'Prihlásenie';
      $_SESSION["admin"]["msg"]["popis"] = "Neplatné relácia užívateľa";
      $_SESSION["admin"]["msg"]["scope"] = 'login';
      $_SESSION["admin"]["msg"]["back_url"] = DIR_WWW_ROOT . 'app/index.html';
      HEADER("Location: " . DIR_WWW_ROOT . "msg/i/error/login.html");
      exit();
    }
    break;
    /* #endregion */
    /* #region  "logout" */
  case "logout":
    unset($_SESSION["admin"]["auth"]);
    $_SESSION["admin"]["auth"]["status"] = false;
    HEADER("Location: " . DIR_WWW_ROOT . "app/index.html");
    exit();
    break;
    /* #endregion */
    /* #region  "password-change" */
  case "password-change":
    $post_params = $sec->allowed_post_params(array('pwdA', 'pwdB'));
    $pwdA = $post_params['pwdA'];
    $pwdB = $post_params['pwdB'];

    if (strlen(trim($pwdA)) < 8) {
      $_SESSION["admin"]["msg"]["status"] = true;
      $_SESSION["admin"]["msg"]["nadpis"] = 'Zmena hesla - CHYBA';
      $_SESSION["admin"]["msg"]["popis"] = "Heslo sa nepodarilo zmeniť. Zadané nové heslo musí mať minimálne 8 znakov!";
      $_SESSION["admin"]["msg"]["scope"] = 'password-change';
      $_SESSION["admin"]["msg"]["back_url"] = DIR_WWW_ROOT . 'app/zmena-hesla.html';
      HEADER("Location: " . DIR_WWW_ROOT . "msg/i/error/password-change.html");
      exit();
    }

    if ($pwdA == $pwdB) {
      $heslo = password_hash($pwdA, PASSWORD_ARGON2I);
      $hash = md5(time() . mt_rand(1000, 9999));

      $sql = "UPDATE " . TBL_USER . " SET pwd = :pwd, hash = :hash WHERE id = :id";
      $stmt = $db_pdo->prepare($sql);
      $stmt->bindValue(':pwd', $heslo, PDO::PARAM_STR);
      $stmt->bindValue(':hash', $hash, PDO::PARAM_STR);
      $stmt->bindValue(':id', $_SESSION["admin"]["auth"]["id_user"], PDO::PARAM_INT);
      $stmt->execute();
      if ($stmt->rowCount()>0) {
        $_SESSION["admin"]["msg"]["status"] = true;
        $_SESSION["admin"]["msg"]["nadpis"] = 'Zmena hesla';
        $_SESSION["admin"]["msg"]["popis"] = "Heslo bolo úspešne zmenené";
        $_SESSION["admin"]["msg"]["scope"] = 'password-change';
        $_SESSION["admin"]["msg"]["back_url"] = DIR_WWW_ROOT . 'app/index.html';
        HEADER("Location: " . DIR_WWW_ROOT . "msg/i/success/password-change.html");
        exit();
      } else {
        $_SESSION["admin"]["msg"]["status"] = true;
        $_SESSION["admin"]["msg"]["nadpis"] = 'Zmena hesla - CHYBA';
        $_SESSION["admin"]["msg"]["popis"] = "Heslo sa nepodarilo zmeniť. Pri zápise do databázy došlo k chybe. Prosím zopakujte akciu a v prípade chyby kontaktujte správcu systému.";
        $_SESSION["admin"]["msg"]["scope"] = 'password-change';
        $_SESSION["admin"]["msg"]["back_url"] = DIR_WWW_ROOT . 'app/zmena-hesla.html';
        HEADER("Location: " . DIR_WWW_ROOT . "msg/i/error/password-change.html");
        exit();
      }


    } else {
      $_SESSION["admin"]["msg"]["status"] = true;
      $_SESSION["admin"]["msg"]["nadpis"] = 'Zmena hesla - CHYBA';
      $_SESSION["admin"]["msg"]["popis"] = "Heslo sa nepodarilo zmeniť. Zadané nové heslo a jeho kontrola nie sú rovnaké!";
      $_SESSION["admin"]["msg"]["scope"] = 'password-change';
      $_SESSION["admin"]["msg"]["back_url"] = DIR_WWW_ROOT . 'app/zmena-hesla.html';
      HEADER("Location: " . DIR_WWW_ROOT . "msg/i/error/password-change.html");
      exit();
    }

    break;
    /* #endregion */
}
