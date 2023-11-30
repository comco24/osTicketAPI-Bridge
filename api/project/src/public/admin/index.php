<?php
session_start();

require_once("sett.php");
require_once("conf/env_conf.php");
require_once("classes/basic.php");
require_once("classes/sec.php");
require_once("inc/db_open.php");

$sec=new sec();

require_once("inc/ini_global_vars.php");

$basic=new basic();

require_once("inc/ini_vars.php");

?>
<!doctype html>
<html lang="sk">
<?php
require_once("inc/inc_header.php");
?>
<body>
<?php
if ($_SESSION["admin"]["msg"]["status"] == true)
{
  require_once("inc/inc_msg.php");
}
else
{
  if ($_SESSION["admin"]["auth"]["status"]==false)
  {
    require_once("inc/inc_login.php");
  }
  else
  {
    require_once("inc/inc_pg.php");
  }
}
require_once("inc/inc_footer.php");
?>
</body>
</html>