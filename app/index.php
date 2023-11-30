<?php
session_start();
$url_prefix = '';

require_once("sett.php");
require_once("conf/env_conf.php");
require_once("class/basic.php");
require_once("class/sec.php");
require_once("inc/db_open.php");

$sec = new sec();

require_once("inc/ini_global_vars.php");

$basic = new basic();

require_once("inc/ini_vars.php");
?>
<!doctype html>
<html lang="sk">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="<?php echo DIR_WWW_ROOT; ?>css/bootstrap.min.css">
  <!-- main CSS -->
  <link rel="stylesheet" href="<?php echo DIR_WWW_ROOT; ?>css/main.css?ver=<?php echo filemtime('css/main.css'); ?>">

  <link href="https://fonts.googleapis.com/css?family=Hind:300,400,700|Lora:400,700&display=swap" rel="stylesheet">

  <title><?php echo PROJECT_TITLE; ?></title>
  <script type="text/javascript" language="javascript">
    var g_www_root = "<?php echo DIR_WWW_ROOT; ?>";
  </script>
  <script src="<?php echo DIR_WWW_ROOT; ?>js/jquery-3.3.1.min.js"></script>
  <script>
    var $j = jQuery.noConflict();
    //alert("Version: "+$j.fn.jquery);
  </script>
  <script src="https://kit.fontawesome.com/3dba06ae66.js"></script>
</head>

<body>
  <div class="container">

    <h1 class="t-lora t-green t-700 text-center my-2">osTicket Form / FAQ generator</h1>

    <?php
    include('inc/inc_pg.php');
    ?>
  </div>
  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->

  <script src="<?php echo DIR_WWW_ROOT; ?>js/popper.min.js"></script>
  <script src="<?php echo DIR_WWW_ROOT; ?>js/bootstrap.min.js"></script>
  <script src="<?php echo DIR_WWW_ROOT; ?>js/main.js?v=<?php echo filemtime('js/main.js'); ?>"></script>
  <script src="<?php echo DIR_WWW_ROOT; ?>js/plugins.js"></script>
  <script src="<?php echo DIR_WWW_ROOT; ?>js/sly.min.js"></script>
</body>

</html>