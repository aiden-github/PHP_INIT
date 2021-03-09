<?php

date_default_timezone_set('Asia/Seoul');

// COMMON
define('ROOT', $_SERVER['DOCUMENT_ROOT']);
define('ASSETS', '/src/assets');
define('CURRENT_TIME', date('Y/m/d H:i:s'));
define('METHOD', $_SERVER['REQUEST_METHOD']);

// URL
define('URL_SELF', htmlspecialchars($_SERVER["PHP_SELF"]));
define('URL_HOME', '/');


// MODULE
include 'dbconfig.php';