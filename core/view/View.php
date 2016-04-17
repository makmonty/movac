<?php

namespace view;

class View {

	private $vars;
	private $page;
	private $errors;
	private $notices;
	private $controller_instance;

	public static $engine;

	public function __construct() {
		$this->vars = array();
		$this->errors = array();
		$this->notices = array();
	}

	public function setControllerInstance($controller_instance) {
		$this->controller_instance = $controller_instance;
	}

	public function addVar($key, $value) {
		$this->vars[$key] = $value;
	}

	public function addVarsFromObject($object) {
		$vars = get_object_vars($object);
		foreach($vars as $name => $value) {
			$this->addVar($name, $value);
		}
	}

	public static function isRemoteUrl($path) {
		return substr($path,0,7) == "http://" || substr($path,0,2) == "//" || substr($path,0,8) == "https://" || substr($path,0,6) == "ftp://";
	}

	public static function addTemplatesDir($directory) {
		self::$engine->addTemplateDir($directory);
	}

	public static function addPluginsDir($directory) {
		self::$engine->addPluginsDir($directory);
	}

	public function show($page = null) {
		$this->page = $page;

		foreach($this->vars as $key => $value) {
			self::$engine->assign($key, $value);
		}

		if($page) {
		} else {
			$this->insertResources();
			self::$engine->assign("template_file", "");
			if(!$this->hidelayout) {
				self::$engine->display($this->layout .".tpl");
			}
		}
	}
}
