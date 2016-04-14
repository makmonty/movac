<?php

namespace plugin;

class Plugin {
	private static $active = array();
	private static $baseDir = "";

	public static function activate($name, $path = "") {
		if(!in_array($name, self::$active)) {
			self::$active[] = $name;
			self::initPlugin($name, $path);
		}
	}

	public static function getActive() {
		return self::$active;
	}

	public static function setBaseDir($dir) {
		self::$baseDir = $dir;
	}

	public static function getBaseDir() {
		return self::$baseDir != "" ? self::$baseDir : dirname($_SERVER["SCRIPT_FILENAME"]) . "/plugins/";
	}

	private static function initPlugin($name, $path = "") {
		$location = $path != "" ? $path : self::getBaseDir() . $name;
		require_once($location . "/init.php");
	}
}
