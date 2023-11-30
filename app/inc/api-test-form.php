<?php
require_once("private/api-conf.php");
require_once("class/x_api.php");

$api = new x_api(X_API_S_KEY, X_API_APP_ID, X_API_URL);
$topic_data = $api->getOSTData($id);
$form = $api->generateForm($topic_data);
echo $form;
