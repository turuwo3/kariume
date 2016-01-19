<?php

namespace TRW\Core;

class App {

	public static function className($class, $type){
		$base = Configure::read('App', 'namespace');
		$fullName = '\\' . $type . '\\' . $class;
		
		if(class_exists($base . $fullName)){
			return $base . $fullName;
		}
		if(class_exists('TRW' . $fullName)){
			return 'TRW' . $fullName;
		}

		return false;
	}

	public static function securitySalt(){
		return Configure::read('Security', 'salt');
	}

	public static function path($type){
		return Configure::read('App', $type);
	}

}
