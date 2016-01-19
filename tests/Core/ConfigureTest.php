<?php

require '../../vendor/autoload.php';

use TRW\Core\Configure;

class ConfigureTest extends PHPUnit_Framework_TestCase {
		
	public function testLoadAndRead(){
		Configure::load('../app/config/config.php');

		$this->assertEquals('App', Configure::read('App', 'namespace'));
	}
			
		
		
}
