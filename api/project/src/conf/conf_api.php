<?php
    define('HTTP_SERVER', '');
    define('DIR_ROOT', '/tikety/api/');

    define('DIR_ROOT_FRONTEND', DIR_ROOT);
    define('DIR_WWW_ROOT', HTTP_SERVER.DIR_ROOT);

    define('LOCALHOST_URL_PART','');

    define('DB_SERVER', '');
    define('DB_SERVER_USERNAME', '');
    define('DB_SERVER_PASSWORD', '');
    define('DB_DATABASE', '');
    define('DB_SERVER_PORT', '');

    define('API_URL', '');
    define('API_KEY', '');

define('TBL_API_ACCESS', 'api_access');
define('TBL_API_COM', 'api_communication');


define('TBL_HELP_TOPIC', 'ost_help_topic');
define('TBL_HT_FORM', 'ost_help_topic_form');
define('TBL_FORM', 'ost_form');
define('TBL_F_ENTRY', 'ost_form_entry');
define('TBL_F_FIELD', 'ost_form_field');
define('TBL_LIST', 'ost_list');
define('TBL_L_ITEMS', 'ost_list_items');
define('TBL_FAQ', 'ost_faq');
define('TBL_FAQ_CATEGORY', 'ost_faq_category');
define('TBL_FAQ_TOPIC', 'ost_faq_topic');

define('VALID_WINDOW', 1800);

define('SKEY',"");

define('FLAG_CLIENT_VIEW', 256);
define('FLAG_CLIENT_EDIT', 512);
define('FLAG_CLIENT_REQUIRED', 1024);
define('MASK_CLIENT_FULL', 1792);

$app_ids = array(
    array('id' => '7152977138', 's_key' => '') // app id with key
);
?>
