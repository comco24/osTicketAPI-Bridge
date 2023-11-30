<?php
require_once '../../sett.php';
require_once '../../conf/env_conf.php';
require_once '../../classes/basic.php';
require_once '../../classes/sec.php';
require_once '../../include/db_open.php';

$sec=new sec();
$basic=new basic();

$post_params = $sec->allowed_post_params(array('source'));
$source = $post_params["source"];
if ((strlen(trim($source))<2) && (isset($_GET["source"])))
{
    $get_params = $sec->allowed_get_params(array('source'));
    $source = $get_params["source"]; 
}
//echo 'source:'.$source.'<br>';
switch ($source)
{
    case "zakaznici_filter":
        $post_params = $sec->allowed_post_params(array('datum_od','suma','podmienka'));
        $datum_od = $post_params["datum_od"];
        $suma = $post_params["suma"];
        $podmienka = $post_params["podmienka"];
        $_SESSION["filter_datum"] = $datum_od;
        $_SESSION["filter_suma"] = $suma;
        $_SESSION["filter_podmienka"] = $podmienka;
        HEADER("Location: ".DIR_WWW_ROOT."admin/index.php?pg=zakaznici&akcia=filter");
        exit();
        break;
    case "inzeraty":
        $post_params = $sec->allowed_post_params(array('page','recsOnPage','orderOnPage','category_filter'));
        $page = $post_params["page"];
        $recsOnPage = $post_params["recsOnPage"];
        $orderOnPage = $post_params["orderOnPage"];
        $category_filter = $post_params["category_filter"];
        //echo 'recsOnPage:'.$recsOnPage.'<br>page:'.$page;exit();
        $_SESSION["site"]["records_on_page"] = (int)$recsOnPage;
        $_SESSION["site"]["order_on_page"] = (int)$orderOnPage;
        $_SESSION["site"]["category_filter"] = (int)$category_filter;
        HEADER("Location: ".DIR_WWW_ROOT."inzeraty/strana-".$page.".html");
        exit();
        break;
    default:
        HEADER("Location: ".DIR_WWW_ROOT."");
        exit();
        break;
}
?>
