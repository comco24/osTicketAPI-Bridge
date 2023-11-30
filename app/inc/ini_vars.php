<?php
/*

inicializacia premennych pre konkretnu podstranku

*/

if (isset($_SESSION["site"]["auth"]["status"]) && $_SESSION["site"]["auth"]["status"] == true) {
  $sec->confirm_session_is_valid("site");
}



if (!isset($pg)) {
  $pg = '';
  $get_params = $sec->allowed_get_params(array('pg'));
  $pg = $get_params["pg"];

  if ((strlen(trim($pg)) < 2) && (isset($_POST["pg"]))) {
    $post_params = $sec->allowed_post_params(array('pg'));
    $pg = $post_params["pg"];
  }
}


switch ($pg) {
  case "form":
  case "api-form":
  case "api-faq":
    $get_params = $sec->allowed_get_params(array('id'));
    $id = $get_params['id'];
    break;
  default:
    $file = $_SERVER["SCRIPT_NAME"];
    $break = Explode('/', $file);
    $pfile = $break[count($break) - 1];
    switch ($pfile) {
      case "message.php":
        $get_params = $sec->allowed_get_params(array('status'));
        $status = $get_params['status'];
        if ($status == 'ok') {
          $get_params = $sec->allowed_get_params(array('ticket_id'));
          $ticket_id = $get_params['ticket_id'];
        }
        break;
    }
    break;
}

if (!isset($scope)) {
  $scope = '';
}
$basic->checkMsgScope($scope);
