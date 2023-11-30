<?php
if (session_status() == PHP_SESSION_NONE) 
{
  session_start();
}
define('LOCALHOST', 1);
if (LOCALHOST==1)
{
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('max_execution_time', 60);
}
?>
