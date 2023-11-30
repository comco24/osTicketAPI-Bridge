<?php
if ($status=='ok') {
  $h2 = 'Formulár bol úspešne odoslaný.';
  $text = 'ID nového ticketu je: <strong>'.$ticket_id.'</strong>';
} else {
  $h2 = 'Chyba.';
  $text = 'Formulár nebol odoslaný.';
}
?>
<h2 class="t-lora t-700 t-blue text-center my-3"><?php echo $h2; ?></h2>
<div class="row">
  <div class="col-12 zoznam text-center mt-2">
    <?php
    echo $text;
    ?>
  </div>
</div>
<div class="row mt-5">
  <div class="col-12 text-center">
    <a href="<?php echo DIR_WWW_ROOT; ?>" class="general-green-btn">späť</a>
  </div>
</div>