<?php

//date_default_timezone_set("Europe/Madrid");

function __lib_autoload($classname) {
	$a = explode("\\", $classname);
	$path = __DIR__ . "/" . implode("/", $a) . ".php";
	if(file_exists($path)) {
		require_once($path);
	}
}

spl_autoload_register("__lib_autoload");
