<?php
  define('HTTP_SERVER', '');
  define('DIR_ROOT', '/tikety/app/');

  define('DIR_ROOT_FRONTEND', DIR_ROOT);
  define('DIR_ADMIN', HTTP_SERVER . DIR_ROOT . 'admin/');
  define('DIR_WWW_ROOT', HTTP_SERVER . DIR_ROOT);

  define('DB_SERVER', '');
  define('DB_SERVER_USERNAME', '');
  define('DB_SERVER_PASSWORD', '');
  define('DB_DATABASE', '');
#  define('DB_SERVER__PORT', '3313');

  define('API_URL', '');
  define('API_KEY', '');

define('TBL_HELP_TOPIC', 'ost_help_topic');
define('TBL_HT_FORM', 'ost_help_topic_form');
define('TBL_FORM', 'ost_form');
define('TBL_F_ENTRY', 'ost_form_entry');
define('TBL_F_FIELD', 'ost_form_field');
define('TBL_LIST', 'ost_list');
define('TBL_L_ITEMS', 'ost_list_items');

define('PROJECT_TITLE', 'osTicket - API');

error_reporting(0);
