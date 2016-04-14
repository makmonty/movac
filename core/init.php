<?php

//date_default_timezone_set("Europe/Madrid");

if(dirname($_SERVER['PHP_SELF']) == "/")
	define('ROOT', "/");
else
	define('ROOT', dirname($_SERVER['PHP_SELF'])."/");

define("BASE_DIR", dirname(__FILE__) ."/../");

require_once(BASE_DIR ."environment.default.php");


function __lib_autoload($classname) {
	$a = explode("\\", $classname);
	$path = BASE_DIR ."core/". implode("/", $a) .".php";
	if(file_exists($path)) {
		require_once($path);
	}
}

spl_autoload_register("__lib_autoload");
