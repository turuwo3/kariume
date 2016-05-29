<?php

namespace TRW\Core;

/**
* アプリケーションの設定ファイルを表すクラス.
*
* Configureクラスのラッパーのようなもの
*
*/
class App {

/**
* コンフィグファイルに定義された名前空間からクラスを探す.
*
* @return string|boolean クラスが見つかればそのクラス名を返す
* 見つからなければfalseを返す
*/
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

/**
* securitySaltの値を返す.
*
* @return string
*/
	public static function securitySalt(){
		return Configure::read('Security', 'salt');
	}

/**
* アプリケーションのディレクトリパスを返す.
*
* @return array
*/
	public static function path($type){
		return Configure::read('App', $type);
	}

}
