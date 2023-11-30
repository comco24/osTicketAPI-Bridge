<?php
/*
 * API interface (uses Request + Response from Slim framework)
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';
require 'project/src/conf/conf_api.php';
require 'project/src/class/basic.php';
require 'project/src/class/sec.php';
require 'project/src/inc/db_open.php';


$basic = new basic();
$sec = new sec();

$app = new \Slim\App([
  'settings' => [
    'displayErrorDetails' => true,
  ]
]);

$app->post('/hi', function (Request $request, Response $response) {
  global $app_ids;
  global $basic;

  $body = $request->getBody();
  $input = json_decode($body);

  $start_time = $basic->writeReqToDB_a($body);

  $app_id = $input->b;
  $public_key = $input->c;
  $hash = $input->d;

  $key = array_search($app_id, array_column($app_ids, 'id'));


  if ($key === false) {
    $output = json_encode(array(
      "result" => 0,
      "result_text" => "komunikácia odmietnutá"
    ));
    $status = 403;
  } else {
    $s_key = $app_ids[$key]["s_key"];

    $my_hash = $basic->get_app_hash($app_id, $public_key, $s_key);
    if ($my_hash == $hash) {
      $ip_address = $basic->getIP();

      $unique_id = str_replace(".", "_", uniqid('', true));
      $ts = date("Y-m-d H:i:s");

      $params = array('app_id' => $app_id, 'access_id' => $unique_id, 'timestamp' => $ts, 'valid_window' => VALID_WINDOW, 'ip_address' => $ip_address);

      $basic->writeToDB('access_id', $params);

      $output = json_encode(array(
        "app_id" => $app_id,
        "result" => 1,
        "result_text" => "komunikácia povolená",
        "access_id" => $unique_id,
        "timestamp" => $ts,
        "valid_window" => VALID_WINDOW
      ));
      $status = 200;
    } else {
      $output = json_encode(array(
        "result" => 0,
        "result_text" => "komunikácia odmietnutá"
      ));
      $status = 403;
    }
  }

  $basic->writeReqToDB_b($output, $start_time);

  $response = $response->withStatus($status)
    ->withHeader('Content-type', 'application/json;charset=utf-8')
    ->write($output);

  return $response;
});


$app->post('/process', function (Request $request, Response $response) {
  global $app_ids;
  global $basic;

  $body = $request->getBody();
  $input = json_decode($body);

  $start_time = $basic->writeReqToDB_a($body);

  $app_id = $input->b;
  $access_id = $input->e;
  $req = $input->a;

  $key = array_search($app_id, array_column($app_ids, 'id'));



  if ($key === false) {
    $output = json_encode(array(
      "result" => 0,
      "result_text" => "komunikácia odmietnutá"
    ));
    $status = 403;
  } else {
    $ip_address = $basic->getIP();
    $ts = date("Y-m-d H:i:s");

    if ($basic->isAccessIdValid($app_id, $access_id, $ip_address, $ts)) {
      switch ($req) {
        case "get_form":
          $topic_id = $input->r;
          $r = $basic->apiAPP_getTopic($topic_id);
          $params = array('app_id' => $app_id, 'timestamp' => $ts, 'result' => $r["result"], 'result_text' => $r["result_text"], 'topic' => $r["topic"]);
          $output = json_encode($params);
          $status = $r["status"];
          break;
        case "get_faqs":
          if (isset($input->id)) {
            $topic_id = $input->id;
          } else {
            $topic_id = 0;
          }
          $r = $basic->apiAPP_getFaqs($topic_id);
          $params = array('app_id' => $app_id, 'timestamp' => $ts, 'result' => $r["result"], 'result_text' => $r["result_text"], 'category' => $r["category"]);
          $output = json_encode($params);
          $status = $r["status"];
          break;
        case "get_faq":
          $id = (int)$input->r;
          $r = $basic->apiAPP_getFaq($id);
          $params = array('app_id' => $app_id, 'timestamp' => $ts, 'result' => $r["result"], 'result_text' => $r["result_text"], 'faq' => $r["faq"], 'faq_id' => $id);
          $output = json_encode($params);
          $status = $r["status"];
          break;
        case "get_faqs_categories":
          $id = (int)$input->r;
          $r = $basic->apiAPP_getFaqsCategories($id);
          $params = array('app_id' => $app_id, 'timestamp' => $ts, 'result' => $r["result"], 'result_text' => $r["result_text"], 'category' => $r["category"]);
          $output = json_encode($params);
          $status = $r["status"];
          break;
        case "get_faqs_topic":
          if (isset($input->r)) {
            $topic_id = $input->r;
          } else {
            $topic_id = 0;
          }
          $r = $basic->apiAPP_getFaqsTopic($topic_id);
          $params = array('app_id' => $app_id, 'timestamp' => $ts, 'result' => $r["result"], 'result_text' => $r["result_text"], 'faqs' => $r["faqs"], 'topic_id' => $r["topic_id"]);
          $output = json_encode($params);
          $status = $r["status"];
          break;
        default:
          $output = json_encode(array(
            "result" => 0,
            "result_text" => "neplatná požiadavka"
          ));
          $status = 403;
          break;
      }
    } else {
      $output = json_encode(array(
        "result" => 0,
        "result_text" => "komunikácia odmietnutá"
      ));
      $status = 403;
    }
  }

  $basic->writeReqToDB_b($output, $start_time);

  $response = $response->withStatus($status)
    ->withHeader('Content-type', 'application/json;charset=utf-8')
    ->write($output);

  return $response;
});

$app->run();
