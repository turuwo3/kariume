<?php
require '../vendor/autoload.php';

use TRW\Core\Configure;
use TRW\Exception\ErrorHandler;
use TRW\Exception\ExceptionRenderer;
use TRW\Exception\MissingControllerException;
use TRW\Exception\NotFoundException;

class ExceptionTest extends PHPUnit_Framework_TestCase {




	public  static function setUpBeforeClass(){
		Configure::load('../app/config/config.php');
	}

	public function testExceptionRender(){
		Configure::load('../app/config/test.config.php');
		$this->expectOutputString('<div>エラーが発生しました
</div>
');
		ErrorHandler::handleException(new Exception('error'));
	}


}


/*
*		Configure::load('../app/config/config.php');
*		set_exception_handler('TRW\Exception\ErrorHandler::handleException');
*		throw new MissingControllerException('Controller Notfound');		
*/	
