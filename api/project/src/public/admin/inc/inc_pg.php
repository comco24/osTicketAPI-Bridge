<?php
include('inc_top_page.php');
?>
  <div class="row">
    <div class="col-sm-10 offset-sm-1">
<?php
switch($pg)
{
  case "msg":
    include('inc_msg.php');
    break;
    case "zmena-hesla":
    include('inc_zmena_hesla.php');
    break;
  case "menu":
  default:
    include('inc_menu.php');
    break;
}
?>
    </div>
  </div>
<?php
//include('inc_bottom_page.php');
?>