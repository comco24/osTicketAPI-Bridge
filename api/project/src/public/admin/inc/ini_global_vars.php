<?php
if (!isset($_SESSION["admin"]["global"]["lang"]) || $_SESSION["admin"]["global"]["lang"]=='')
{
  $_SESSION["admin"]["global"]["lang"] = 'sk';
}
if (!isset($_SESSION["admin"]["msg"]["status"]) || $_SESSION["admin"]["msg"]["status"]=='')
{
  $_SESSION["admin"]["msg"]["status"] = false;
}

/*
 * $url_prefix - inicializacia premennej (pouziva sa pri includovani suborov
 * v podpriecinkoch)
 */
$url_prefix = '';
?>
