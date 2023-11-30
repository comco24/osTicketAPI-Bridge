<?php


if (!isset($_SESSION["site"]["auth_id_user"]))
{
  $_SESSION["site"]["auth_id_user"] = 0;
}

/*
 * $url_prefix - inicializacia premennej (pouziva sa pri includovani suborov
 * v podpriecinkoch)
 */
if (LOCALHOST==0){
  $url_prefix = '/';
}
else {
  $url_prefix = '';
}

?>
