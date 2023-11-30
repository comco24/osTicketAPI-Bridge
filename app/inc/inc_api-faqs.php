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


// getOSTFaqsData() vracia jSON objekt so vsetkymi aktivnym FAQ polozkami vratane kategorii
$faqs_data = $api->getOSTFaqsData();

// generateForm() spracuje jSON objekt a vygeneruje formular
$faqs = $api->generateFaqsCategory($faqs_data, FRM_BOOTSTRAP_4, DIR_WWW_ROOT.'api-faq/');
echo $faqs;
?>
<div class="row my-5">
  <div class="col-12 text-center pb-5">
    <a href="<?php echo DIR_WWW_ROOT; ?>" class="general-green-btn">späť</a>
  </div>
</div>