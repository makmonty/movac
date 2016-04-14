<?php

namespace i18n;

class LanguageDetector {
	public $valid_languages = array();
	
	public function __construct($valid) {
		$this->valid_languages = $valid;
	}
	
	public function getUserLanguage() {
// 		$serverArray = explode(".", SERVER);
		if(isset($_GET['l'])) {
			$l = $_GET['l'];
			\session\Session::set('lang', $l);
// 		} else if(in_array($serverArray[0], $this->valid_languages)) {
// 			$l = $serverArray[0];
		} else if(\session\Session::issetKey('lang')) {
			$l = \session\Session::get('lang');
		} else if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
			$l = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
		} else {
			$l = "es";
		}
		
		return $l;
	}
}
