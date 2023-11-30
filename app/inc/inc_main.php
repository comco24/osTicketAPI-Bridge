<?php
$SQL = "SELECT * FROM " . TBL_HELP_TOPIC . " ORDER BY `sort`";
$stmt = $db_pdo->prepare($SQL);
$stmt->execute();
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

$cache_help_topic_list = $basic->generateHPList($records,'');
?>
<h2 class="t-lora t-700 t-blue text-center my-2">Zoznam formulárov:</h2>
<div class="row">
  <div class="col-12 zoznam mt-5">
    <?php
    echo $cache_help_topic_list;
    ?>
  </div>
</div>
<?php
$cache_help_topic_list = $basic->generateHPList($records,'api-');
?>
<h2 class="t-lora t-700 t-blue text-center my-2">generovanie formuláru cez API:</h2>
<div class="row">
  <div class="col-12 zoznam mt-5">
    <?php
    echo $cache_help_topic_list;
    ?>
  </div>
</div>
<h2 class="t-lora t-700 t-blue text-center my-2">generovanie faqs cez API:</h2>
<div class="row">
  <div class="col-12 text-center mt-5">
  <a href="<?php echo DIR_WWW_ROOT; ?>api-faqs.html" class="general-green-btn">zobraziť FAQs</a>
  </div>
</div>