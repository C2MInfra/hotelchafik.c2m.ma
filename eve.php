<?php
session_start();
error_reporting(1);
ini_set('display_errors', 1);
ini_set('memory_limit', '512M');


define('SERVER', getenv('DB_HOST') ?: '172.18.0.3');
define('DATABASE', getenv('DB_NAME') ?: 'commission');
define('USER', getenv('DB_USER') ?: 'root');
define('PASSWORD', getenv('DB_PASSWORD') ?: 'root');
define('BASE_URL', getenv('BASE_URL') ?: 'http://localhost/commision.c2m.ma/');

function __autoload($class_name)
{
	include_once('model/' . $class_name . '.php');
}
