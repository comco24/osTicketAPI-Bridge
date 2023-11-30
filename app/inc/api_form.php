<?php
session_start();

$url_prefix = '../';

require_once("../sett.php");
require_once("../conf/env_conf.php");
require_once("../class/basic.php");
require_once("../class/sec.php");
require_once("db_open.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$sec = new sec();

require_once("../inc/ini_global_vars.php");

$basic = new basic();

// vygenerujeme hash
$public_key = $basic->getRandomString(32);
$hash = hash('sha256', X_API_S_KEY . X_API_APP_ID . $public_key);

$data = array('b' => X_API_APP_ID, 'c' => $public_key, 'd' => $hash);


$ch = curl_init(X_API_URL . 'hi');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$result = curl_exec($ch);
echo '1<br>';
var_dump($result);
$res_array = json_decode($result,true);
echo 'result: ' . $res_array["result"] . '<br>';
echo 'access_id: ' . $res_array["access_id"] . '<br>';
echo 'timestamp: ' . $res_array["timestamp"] . '<br>';
if ($res_array["result"] == 1) {
  // vytvorime poziadavku na formular:
  $access_id = $res_array["access_id"];
  $data = array('b' => X_API_APP_ID, 'e' => $access_id, 'a' => 'get_form', 'r' => 12);

  $ch = curl_init(X_API_URL . 'process');
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  $result = curl_exec($ch);
  $res_array = json_decode($result,true);
  echo '<pre>';
  var_dump($res_array);
  echo '</pre>';
}
