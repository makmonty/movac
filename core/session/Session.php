<?php

namespace session;

class Session {
	
	public static function get($key) {
		//@session_start();
		$value = $_SESSION[$key];
		//session_write_close();
		return $value;
	}
	
	public static function set($key, $value) {
		//@session_start();
		$_SESSION[$key] = $value;
		//session_write_close();
	}
	
	public static function issetKey($key) {
		//@session_start();
		$value = isset($_SESSION[$key]);
		//session_write_close();
		return $value;
	}
	
	public static function unsetKey($key) {
		//@session_start();
		unset($_SESSION[$key]);
		//session_write_close();
	}
}