<div class="container-fluid">

<div class="row">
  <div class="col-12 top_page_box">
    <div class="container-fluid">
    <div class="row tpb">
      <div class="col-6 text-left">
        <div class="std dsp-tbl">
          <div class="dsp-tbl-cell cp-off"><a href="<?php echo DIR_WWW_ROOT ?>act/logout/"><i class="fas fa-lg fa-power-off"></i></a></div>
          <div class="dsp-tbl-cell">
            užívateľ: <strong><?php echo $_SESSION["admin"]["auth"]["email"]; ?></strong><br />
            <?php
            switch($_SESSION["admin"]["auth"]["id_type"])
            {
              case 1:
                $opravnenie = 'administrátor';
                break;
            }
            ?>
            oprávnenie: <strong><?php echo $opravnenie; ?></strong>
          </div>
        </div>
      </div>
      <div class="col-6 text-right">
        <div class="std">
          <img src="<?php echo LOGO_SMALL; ?>" alt="<?php echo SERVICE_TITLE_NAME; ?>" />
        </div>
      </div>
    </div>
    </div>
  </div>
</div>

</div>

<div class="container-fluid">
<div class="row top_page_actions">
    <div class="col-12 text-center" style="padding:20px;">
<?php
switch($pg)
{
  case "zmena-hesla":
    ?>
    <a href="<?php echo DIR_WWW_ROOT ?>app/index.html" class="adm_btn_action"><strong>Späť do hlavného menu</strong></a>
    <?php
    break;
  default:
      ?>

      <?php
      break;
}
?>
    </div>
</div>
</div>