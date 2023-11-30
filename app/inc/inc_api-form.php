<?php
/*
inc_api-form.php
Skript sa pripaja na API odkial ziska vsetky data potrebne k vygenerovaniu formulara z Help topicu. Id Help topicu je predavane v premennej $id. Skript potrebuje dva subory:
  - private/api-conf.php --> konfiguracny subor
  - class/x_api.php --> trieda x_api zabezpecujuca pripojenie a komunikaciu s API rozhranim
*/
require_once("private/api-conf.php");
require_once("class/x_api.php");

$api = new x_api(X_API_S_KEY, X_API_APP_ID, X_API_URL);


// getOSTData() vracia jSON objekt s popisom vsetkych poli formulara
$topic_data = $api->getOSTData($id);
// echo 'topic_data: <br><pre>';
//  $jsd = json_decode($topic_data,true);
//  echo '<pre>';
//  var_dump($jsd);
//  echo '</pre>';
//  exit();


// generateForm() spracuje jSON objekt a vygeneruje formular
$form = $api->generateForm($topic_data, FRM_BOOTSTRAP_4, X_API_R_URL);
echo $form;
?>
<div class="row my-5">
  <div class="col-12 text-center pb-5">
    <a href="<?php echo DIR_WWW_ROOT; ?>" class="general-green-btn">späť</a>
  </div>
</div>