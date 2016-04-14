<?php

namespace hook;

class Hook {
	
	private static $hooks = array();
	
	public static function add($key, $function) {
		self::$hooks[$key][] = $function;
	}
	
	public static function run($key) {
		if(isset(self::$hooks[$key])) {
			foreach(self::$hooks[$key] as $function) {
				call_user_func_array($function, func_get_args());
			}
		}
	}
	
}