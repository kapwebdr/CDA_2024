<?php
require_once __DIR__.'/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/..');
$dotenv->load();

define('DIR_BASE',__DIR__.'/../');
define('DIR_CONFIG',DIR_BASE.'config/');
define('DIR_CONTROLLER',DIR_BASE.'controller/');
define('DIR_MODEL',DIR_BASE.'model/');
define('DIR_PRIVATE',DIR_BASE.'private/');
define('DIR_SCRIPTS',DIR_BASE.'scripts/');
define('DIR_VIEW',DIR_BASE.'view/');

define('DB_HOSTNAME', $_ENV['DB_HOSTNAME']);
define('DB_PORT', $_ENV['DB_PORT']);
define('DB_NAME', $_ENV['DB_NAME']);
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASSWORD', $_ENV['DB_PASSWORD']);

define('COPYRIGHT', $_ENV['COPYRIGHT']);

\Controller\Session::Start();

ini_set("display_errors", "on");
error_reporting(E_ALL);
set_error_handler(['\Controller\Error','PhpError'], E_ALL);
register_shutdown_function(['\Controller\Error','PhpFatalError']);

require_once DIR_CONFIG.'routes.php';
