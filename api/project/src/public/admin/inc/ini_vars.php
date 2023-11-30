<?php
if (isset($_SESSION["admin"]["auth"]["status"]) && $_SESSION["admin"]["auth"]["status"]==true)
{
  $sec->confirm_session_is_valid("admin");
}
else 
{
  $_SESSION["admin"]["auth"]["status"] = false;
}



$page = 1;
if (!isset($pg))
{
  $pg = '';
  $get_params = $sec->allowed_get_params(array('pg'));
  $pg = $get_params["pg"];

  if ((strlen(trim($pg))<2) && (isset($_POST["pg"])))
  {
    $post_params = $sec->allowed_post_params(array('pg'));
    $pg = $post_params["pg"]; 
  }
}


switch ($pg)
{
  case "msg":
    $get_params = $sec->allowed_get_params(array('typ','a','b','id'));
    $typ = $get_params['typ'];
    $a = $get_params['a'];
    $b = $get_params['b'];
    $id = $get_params['id'];
    
    $scope = $b;
    break;
  case "log":
    $get_params = $sec->allowed_get_params(array('action','page'));
    $action = $get_params['action'];
    $page = $get_params['page'];
    break;
  case "portfolio":
    $get_params = $sec->allowed_get_params(array('action','id','page'));
    $action = $get_params['action'];
    $id = $get_params['id'];
    $page = $get_params['page'];
    break;
  case "designer":
    $get_params = $sec->allowed_get_params(array('action','page'));
    $action = $get_params['action'];
    $page = $get_params['page'];
    
    switch($action)
    {
      case "new":
        if (!isset($_SESSION["admin"]["form"]["email"]))
        {
          $_SESSION["admin"]["form"]["email"] = '';
        }
        if (!isset($_SESSION["admin"]["form"]["nickname"]))
        {
          $_SESSION["admin"]["form"]["nickname"] = '';
        }
        if (!isset($_SESSION["admin"]["form"]["phone"]))
        {
          $_SESSION["admin"]["form"]["phone"] = '';
        }
        if (!isset($_SESSION["admin"]["form"]["first_name"]))
        {
          $_SESSION["admin"]["form"]["first_name"] = '';
        }
        if (!isset($_SESSION["admin"]["form"]["last_name"]))
        {
          $_SESSION["admin"]["form"]["last_name"] = '';
        }
        if (!isset($_SESSION["admin"]["form"]["address"]))
        {
          $_SESSION["admin"]["form"]["address"] = '';
        }
        if (!isset($_SESSION["admin"]["form"]["city"]))
        {
          $_SESSION["admin"]["form"]["city"] = '';
        }
        if (!isset($_SESSION["admin"]["form"]["zip"]))
        {
          $_SESSION["admin"]["form"]["zip"] = '';
        }
        if (!isset($_SESSION["admin"]["form"]["id_country"]))
        {
          $_SESSION["admin"]["form"]["id_country"] = 1;
        }
        if (!isset($_SESSION["admin"]["form"]["facebook"]))
        {
          $_SESSION["admin"]["form"]["facebook"] = '';
        }
        if (!isset($_SESSION["admin"]["form"]["instagram"]))
        {
          $_SESSION["admin"]["form"]["instagram"] = '';
        }
        if (!isset($_SESSION["admin"]["form"]["twitter"]))
        {
          $_SESSION["admin"]["form"]["twitter"] = '';
        }
        if (!isset($_SESSION["admin"]["form"]["about_me"]))
        {
          $_SESSION["admin"]["form"]["about_me"] = '';
        }
        if (!isset($_SESSION["admin"]["form"]["status"]))
        {
          $_SESSION["admin"]["form"]["status"] = 1;
        }
        if (!isset($_SESSION["admin"]["form"]["language"]))
        {
          $_SESSION["admin"]["form"]["language"] = 'sk';
        }
        break;
      case "edit":
      case "detail":
        $get_params = $sec->allowed_get_params(array('id'));
        $id = $get_params['id'];
        break;
      case "portfolio":
        $get_params = $sec->allowed_get_params(array('id'));
        $id = $get_params['id'];
        break;
      case "portfolio-edit":
      case "portfolio-detail":
      case "gallery-edit":
      case "gallery-detail":
      case "portfolio-contact-influencer":
        $get_params = $sec->allowed_get_params(array('id','sub_id'));
        $id = $get_params['id'];
        $sub_id = $get_params['sub_id'];
        break;
      case "portfolio-new":
        $get_params = $sec->allowed_get_params(array('id'));
        $id = $get_params['id'];
        
        if (!isset($_SESSION["admin"]["form"]["title"]))
        {
          $_SESSION["admin"]["form"]["title"] = '';
        }
        if (!isset($_SESSION["admin"]["form"]["description"]))
        {
          $_SESSION["admin"]["form"]["description"] = '';
        }
        if (!isset($_SESSION["admin"]["form"]["status"]))
        {
          $_SESSION["admin"]["form"]["status"] = '';
        }
        break;
    }
    
    break;
  case "influencer":
    $get_params = $sec->allowed_get_params(array('action','page'));
    $action = $get_params['action'];
    $page = $get_params['page'];
    
    switch($action)
    {
      case "new":
        if (!isset($_SESSION["admin"]["form"]["email"]))
        {
          $_SESSION["admin"]["form"]["email"] = '';
        }
        if (!isset($_SESSION["admin"]["form"]["nickname"]))
        {
          $_SESSION["admin"]["form"]["nickname"] = '';
        }
        if (!isset($_SESSION["admin"]["form"]["phone"]))
        {
          $_SESSION["admin"]["form"]["phone"] = '';
        }
        if (!isset($_SESSION["admin"]["form"]["first_name"]))
        {
          $_SESSION["admin"]["form"]["first_name"] = '';
        }
        if (!isset($_SESSION["admin"]["form"]["last_name"]))
        {
          $_SESSION["admin"]["form"]["last_name"] = '';
        }
        if (!isset($_SESSION["admin"]["form"]["address"]))
        {
          $_SESSION["admin"]["form"]["address"] = '';
        }
        if (!isset($_SESSION["admin"]["form"]["city"]))
        {
          $_SESSION["admin"]["form"]["city"] = '';
        }
        if (!isset($_SESSION["admin"]["form"]["zip"]))
        {
          $_SESSION["admin"]["form"]["zip"] = '';
        }
        if (!isset($_SESSION["admin"]["form"]["id_country"]))
        {
          $_SESSION["admin"]["form"]["id_country"] = 1;
        }
        if (!isset($_SESSION["admin"]["form"]["facebook"]))
        {
          $_SESSION["admin"]["form"]["facebook"] = '';
        }
        if (!isset($_SESSION["admin"]["form"]["instagram"]))
        {
          $_SESSION["admin"]["form"]["instagram"] = '';
        }
        if (!isset($_SESSION["admin"]["form"]["twitter"]))
        {
          $_SESSION["admin"]["form"]["twitter"] = '';
        }
        if (!isset($_SESSION["admin"]["form"]["about_me"]))
        {
          $_SESSION["admin"]["form"]["about_me"] = '';
        }
        if (!isset($_SESSION["admin"]["form"]["status"]))
        {
          $_SESSION["admin"]["form"]["status"] = 1;
        }
        break;
      case "edit":
      case "detail":
        $get_params = $sec->allowed_get_params(array('id'));
        $id = $get_params['id'];
        break;
    }
    
    break;
  default:
    break;
}

if (!isset($scope))
{
  $scope='';
}
//echo 'scope: '.$scope.'<br>';
//var_dump($_SESSION["admin"]["msg"]);
//exit();
$basic->checkMsgScope($scope);
?>
