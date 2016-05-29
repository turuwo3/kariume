<?php
namespace TRW\Exception;

use Exception;

/**
* 例外発生時、独自の処理を行うクラス.
*
*/
class ErrorHandler {

	private static $renderer = 'TRW\Exception\ExceptionRenderer';

/**
* 例外発生時、適切な例外用ページをレンダリングする
*
* @param Exception $e
*/
	public static function handleException(Exception $e){
		$renderer = static::$renderer;
		$exception = new $renderer($e);
		$exception->callUserFunction();
	}

}
