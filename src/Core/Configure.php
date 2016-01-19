<?php
namespace TRW\Core;

class Configure {

	private static $data;

	public static function _load($filePath){
		return include $filePath;
	}

	public static function load($filePath){
		if(empty(self::$data)){
			self::$data = self::_load($filePath);
		}
	}

	public static function read($name, $key){
		return self::$data[$name][$key];
	}


}
