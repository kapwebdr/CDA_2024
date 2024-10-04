<?php
define('DIR_BASE',__DIR__.'/../');
define('DIR_CONFIG',DIR_BASE.'config/');
define('DIR_CONTROLLER',DIR_BASE.'controller/');
define('DIR_MODEL',DIR_BASE.'model/');
define('DIR_VIEW',DIR_BASE.'view/');

define('DB_HOSTNAME','cda_db');
define('DB_PORT',3306);
define('DB_NAME','cda_db');
define('DB_USER','cda_user');
define('DB_PASSWORD','cda_pwd');

define('COPYRIGHT','Kapweb');

require_once DIR_BASE.'vendor/autoload.php';
require_once DIR_CONFIG.'routes.php';