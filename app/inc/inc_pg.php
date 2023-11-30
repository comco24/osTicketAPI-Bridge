<?php
include('inc_top_page.php');
switch ($pg) {
  case "form":
    include('inc_form.php');
    break;
  case "api-form":
    include('inc_api-form.php');
    break;
  case "api-faq":
    include('inc_api-faq.php');
    break;
  case "api-faqs":
    include('inc_api-faqs.php');
    break;
  case "api-test":
    include('api-test-form.php');
    break;
  case "main":
  default:
    include('inc_main.php');
    break;
}
include('inc_bottom_page.php');
