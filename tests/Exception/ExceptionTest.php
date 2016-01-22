<?php
require '../vendor/autoload.php';

use TRW\Core\Configure;
use TRW\Exception\ErrorHandler;
use TRW\Exception\ExceptionRenderer;
use TRW\Exception\MissingControllerException;
use TRW\Exception\NotFoundException;
use TRW\Exception\ForbiddenException;

class ExceptionTest extends PHPUnit_Framework_TestCase {




	public  static function setUpBeforeClass(){
		Configure::load('../app/config/config.php');
	}

	public function testExceptionRenderCaseException(){
		Configure::load('../app/config/test.config.php');
		$this->expectOutputString('<div>エラーが発生しました
</div>
');
		ErrorHandler::handleException(new Exception('error'));
	}

	public function testExceptionRenderCaseForbiddenException(){
		Configure::load('../app/config/test.config.php');
		$this->expectOutputString('<div><h2>Forbidden</h2>
</div>
');
		ErrorHandler::handleException(new ForbiddenException('error'));
	}


}


/*
*		Configure::load('../app/config/config.php');
*		set_exception_handler('TRW\Exception\ErrorHandler::handleException');
*		throw new MissingControllerException('Controller Notfound');		
*/	
