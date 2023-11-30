<?php
$url = "";

switch($a)
{
  case "account":
  case "success":
  case "error":
    $nadpis = $_SESSION["admin"]["msg"]["nadpis"];
    $text = $_SESSION["admin"]["msg"]["popis"];
    $url = $_SESSION["admin"]["msg"]["back_url"];
    break;
}
if ($url=="")
{
  switch($b)
  {
    default:
      $url = DIR_WWW_ROOT;
      break;
  }
}
?>
<div class="row">
  <div class="col-12 offset-sm-2 col-sm-8 offset-md-3 col-md-6 text-center lwC">
    <img src="<?php echo LOGO_MAIN; ?>" alt="<?php echo SERVICE_TITLE_NAME; ?>" />
  </div>
</div>
<div class="container">
  <div class="row">
    <div class="col-12 offset-sm-2 col-sm-8 offset-md-3 col-md-6 form_area">
      <div class="row">
        <div class="col-12 text-center">
          <form action="<?php echo $url ?>" method="POST">
          <div class="login_window">
            <div class="row">
              <div class="col-12 text-center py-3">
                <?php
                if (isset($nadpis))
                {
                  echo '<h2>'.$nadpis.'</h2>';
                }
                ?>
              </div>
              <div class="col-12 text-center py-3">
                <?php
                if (isset($text))
                {
                  echo $text;
                }
                ?>
              </div>
            </div>
            <div class="row">
              <div class="col-12 text-center lwC">
                <input type="submit" value=" OK " class="form_button" />
              </div>
            </div>
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

