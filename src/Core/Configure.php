<?php
namespace TRW\Core;
/**
* アプリケーションの設定ファイルを表すクラス.
*
*
*/
class Configure {

/**
* コンフィグファイル.
*
* 配列構造をしている
*/
	private static $data;

	public static function _load($filePath){
		return include $filePath;
	}

	public static function load($filePath){
		self::$data = self::_load($filePath);
	}

/**
* コンフィグファイルから値を読み取る.
*
*/
	public static function read($name, $key){
		return self::$data[$name][$key];
	}


}
