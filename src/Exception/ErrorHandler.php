<?php
namespace TRW\Exception;

use Exception;

class ErrorHandler {

	private static $renderer = 'TRW\Exception\ExceptionRenderer';






	public static function handleException(Exception $e){
		$renderer = static::$renderer;
		$exception = new $renderer($e);
		$exception->render();
	}

}
