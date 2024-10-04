<?php
require_once __DIR__.'/../config/config.php';

$users = new \Model\User();
$users->initUsers();

