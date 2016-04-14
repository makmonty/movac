<?php

namespace plugin;

class Plugin {
	private static $active = array();
	private static $baseDir = "";

	public static function activate($name) {
		if(!in_array($name, self::$active)) {
			self::$active[] = $name;
			self::initPlugin($name);
		}
	}

	public static function getActive() {
		return self::$active;
	}

	public static function setBaseDir($dir) {
		self::$baseDir = $dir;
	}

	public static function getBaseDir() {
		return self::$baseDir != "" ? self::$baseDir : BASE_DIR;
	}

	private static function initPlugin($name, $location = "") {
		$location = $location != "" ? $location : self::getBaseDir() . "plugins/" . $name;
		require_once($location . "/init.php");
	}
}
