<?php
session_start();
//session_set_cookie_params(56000);
//echo session_id();
//session_id($user['session_id']);

/*
$_POST
$_GET
$_SERVER
$_SESSION 
*/

// var_dump($_SESSION);
// $_SESSION['user'] = $user;
// $_SESSION['cart'] = $cart;
// unset($_SESSION['user']);
// $_SESSION['user'] = [
//     'username'=>'damien'
// ];
// $_SESSION['permission'] = [
//     'guest'=>true,
//     'editor'=>false,
// ];
// var_dump($_COOKIE);
// session_destroy();

define('DIR_BASE',__DIR__.'/../');
define('DIR_CONFIG',DIR_BASE.'config/');
define('DIR_CONTROLLER',DIR_BASE.'controller/');
define('DIR_MODEL',DIR_BASE.'model/');
define('DIR_VIEW',DIR_BASE.'view/');


/* DB */
define('DB_HOSTNAME','cda_db');
define('DB_PORT',3306);
define('DB_NAME','cda_db');
define('DB_USER','cda_user');
define('DB_PASSWORD','cda_pwd');

/* PhpMailer */
define('SMTP_HOST','');
define('SMTP_USER','');
define('SMTP_PASSWORD','');
define('SMTP_PORT','');
define('SMTP_TLS',false);
define('SMTP_DEBUG',true);

define('COPYRIGHT','Kapweb');

require_once DIR_BASE.'vendor/autoload.php';

ini_set( "display_errors" , "off" );
error_reporting( E_ALL );
set_error_handler(['\Controller\Error','PhpError'],E_ALL);
register_shutdown_function(['\Controller\Error','PhpFatalError']);

require_once DIR_CONFIG.'routes.php';