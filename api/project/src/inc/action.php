<?php
require '../../../project/src/conf/conf_api.php';
require '../../../project/src/class/basic.php';
require '../../../project/src/class/sec.php';
require '../../../project/src/inc/db_open.php';

$basic = new basic();
$sec = new sec();

$get_params = $sec->allowed_get_params(array('action_id'));
$g_action_id = $get_params["action_id"];

if (trim($g_action_id) == "") {
  $post_params = $sec->allowed_post_params(array('action_id'));
  $g_action_id = $post_params["action_id"];
}


function hasFlag($flag, $requested_flag)
{
  return (($flag & $requested_flag) != 0);
}

error_reporting(E_ERROR);
ini_set('display_errors', 0);

$fields = array();
switch ($g_action_id) {
    /* #region  "send-form" */
  case "send-form":
    $post_params = $sec->allowed_post_params(array('topic_id', 'return_url', 'name', 'email', 'form_fields', 'file_fields'));
    $topic_id = $post_params["topic_id"];
    $return_url = $post_params["return_url"];
    $name = $post_params["name"];
    $email = $post_params["email"];
    $form_fields = json_decode($post_params["form_fields"]);
    $form_fields_arr = $sec->allowed_post_params($form_fields);
    if ((!isset($post_params["file_fields"])) || $post_params["file_fields"] == "" || $post_params["file_fields"] == "null") {
      $file_fields = array();
    } else {
      $file_fields = json_decode($post_params["file_fields"]);
    }

    //extract($form_fields_arr);

    $SQL = "SELECT * FROM " . TBL_HELP_TOPIC . " WHERE topic_id = :topic_id";
    $stmt = $db_pdo->prepare($SQL);
    $stmt->bindValue(':topic_id', $topic_id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
      $record = $stmt->fetch(PDO::FETCH_ASSOC);
      $SQL = "SELECT * FROM " . TBL_HT_FORM . " WHERE topic_id = :topic_id ORDER BY `sort`";
      $stmt = $db_pdo->prepare($SQL);
      $stmt->bindValue(':topic_id', $topic_id, PDO::PARAM_INT);
      $stmt->execute();
      if ($stmt->rowCount() > 0) {
        $records_htf = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($records_htf as $record_htf) {
          $disabled = json_decode($record_htf["extra"], true);
          $SQL = "SELECT * FROM " . TBL_FORM . " WHERE id = :id";
          $stmt = $db_pdo->prepare($SQL);
          $stmt->bindValue(':id', $record_htf["form_id"], PDO::PARAM_INT);
          $stmt->execute();
          if ($stmt->rowCount() > 0) {
            $record_form = $stmt->fetch(PDO::FETCH_ASSOC);
            $form_title = $record_form["title"];
            $form_instructions = $record_form["instructions"];
            $field_counter = 0;
            $form_header_show = 1;
            $SQL = "SELECT * FROM " . TBL_F_FIELD . " WHERE form_id = :form_id ORDER BY `sort`";
            $stmt = $db_pdo->prepare($SQL);
            $stmt->bindValue(':form_id', $record_htf["form_id"], PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
              $records_fields = $stmt->fetchAll(PDO::FETCH_ASSOC);
              foreach ($records_fields as $record_field) {
                if (!(in_array($record_field["id"], $disabled["disable"]))) {
                  if (hasFlag($record_field["flags"], FLAG_CLIENT_VIEW)) {
                    $fields[] = $record_field["name"];
                    $field_counter++;
                    switch ($record_field["type"]) {
                      case "text":
                        ${$record_field["name"]} = $form_fields_arr[$record_field["name"]];
                        break;
                      case "files":
                        ${$record_field["name"]} = $_FILES[$record_field["name"]]['name'];
                        $fields[] = $record_field["name"] . '_nazov';
                        ${$record_field["name"] . '_nazov'} = $_FILES[$record_field["name"]]['name'];
                        break;
                      case "thread":
                        ${$record_field["name"]} = $form_fields_arr[$record_field["name"]];
                        break;
                      case "choices":
                        ${$record_field["name"]} = $form_fields_arr[$record_field["name"]];
                        break;
                      case "bool":
                        if ($form_fields_arr[$record_field["name"]] == 1) {
                          ${$record_field["name"]} = 1;
                        } else {
                          ${$record_field["name"]} = 0;
                        }
                        break;
                      case "datetime":
                        $name_den = $record_field["name"] . '_den';
                        $$name_den = $form_fields_arr[$name_den];
                        $name_mesiac = $record_field["name"] . '_mesiac';
                        $$name_mesiac = $form_fields_arr[$name_mesiac];
                        $name_rok = $record_field["name"] . '_rok';
                        $$name_rok = $form_fields_arr[$name_rok];
                        $$record_field["name"] = $$name_rok . '-' . $$name_mesiac . '-' . $$name_den;
                        break;
                      default:
                        $type = explode("-", $record_field["type"]);
#                        switch ($type[0]) {
#                          case "list":
                            ${$record_field["name"]} = $form_fields_arr[$record_field["name"]];
#                        }
                        break;
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
    // vytvorime pole so vsetkymi potrebnymi premennymi:
    $ticket = array('topicId' => $topic_id, 'id' => $_SERVER['REMOTE_ADDR'], 'name' => $name, 'email' => $email);

    foreach ($fields as $field) {
      $ticket[$field] = $$field;
    }

    // zistime ci su vo formulari aj subory
    if (count($file_fields) > 0) {
      // pre kazdy subor zistime ci bol zadany:
      foreach ($file_fields as $file_field) {
        if (file_exists($_FILES[$file_field]['tmp_name']) && is_uploaded_file($_FILES[$file_field]['tmp_name'])) {
          $file_name = $_FILES[$file_field]['name'];
          $file_info = @getimagesize($_FILES[$file_field]['tmp_name']);

          $ticket['attachments'][] =
            array($file_name =>
            'data:' . $_FILES[$file_field]['type'] . ';base64,' .
              base64_encode(file_get_contents($_FILES[$file_field]['tmp_name'])));
        }
      }
    }

    function_exists('curl_version') or die('CURL support required');
    function_exists('json_encode') or die('JSON support required');
    set_time_limit(30);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, API_URL);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($ticket));
    curl_setopt($ch, CURLOPT_USERAGENT, 'osTicket API Client v1.7');
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:', 'X-API-Key: ' . API_KEY));
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $result = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    header("Access-Control-Allow-Origin: *");
    if ($code != 201) {
      #die('Unable to create ticket: ' . $result);
      die('error' ." $result");
    } else {
      $ticket_id = (int) $result;
#      HEADER("Location: " . $return_url . "/ok");
		die('ok');
    }
    break;
    /* #endregion */
}
