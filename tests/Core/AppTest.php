<?php

require '../vendor/autoload.php';

use TRW\Core\Configure;
use TRW\Core\App;

class AppTest extends PHPUnit_Framework_TestCase {

	public function setUp(){
		Configure::load('../app/config/config.php');
	}

	public function testClassName(){
		$type = 'Controller';
		$name = 'UsersController';

		$fullName = App::className($name, $type);

		$this->assertEquals('App\\Controller\\UsersController', $fullName);

	}


}
