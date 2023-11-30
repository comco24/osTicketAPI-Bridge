<?php
if (LOCALHOST == 1) {
  define('HTTP_SERVER', '');
  define('DIR_ROOT', '/');

  define('DIR_ROOT_FRONTEND', DIR_ROOT);
  define('DIR_ADMIN', HTTP_SERVER . DIR_ROOT . 'project/src/public/admin/');
  define('DIR_WWW_ROOT', HTTP_SERVER . DIR_ROOT);

  define('DB_SERVER', '');
  define('DB_SERVER_USERNAME', '');
  define('DB_SERVER_PASSWORD', '');
  define('DB_DATABASE', '');
  define('DB_SERVER_PORT', '3306');
} else {
  define('HTTP_SERVER', '');
  define('DIR_ROOT', '/');

  define('DIR_ROOT_FRONTEND', DIR_ROOT);
  define('DIR_ADMIN', HTTP_SERVER . DIR_ROOT . 'project/src/public/admin/');
  define('DIR_WWW_ROOT', HTTP_SERVER . DIR_ROOT);

  define('DB_SERVER', '');
  define('DB_SERVER_USERNAME', '');
  define('DB_SERVER_PASSWORD', '');
  define('DB_DATABASE', '');
  define('DB_SERVER_PORT', '3313');
}

define('TBL_USER', 'adm_user');

define('MAX_INACTIVITY', 1800);

define('PROJECT_TITLE', 'osTicket - API');
define('SERVICE_TITLE_NAME', 'osTicket - API - admin');
define('LOGO_MAIN', DIR_ADMIN . 'images/logo.png');
define('LOGO_SMALL', DIR_ADMIN . 'images/logo-small.png');
define('DOC_FILE', 'api_rozhranie_os_ticket_0_3_0.pdf');

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
//error_reporting(0);
