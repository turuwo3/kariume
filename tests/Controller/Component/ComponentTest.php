<?php
require '../../vendor/autoload.php';

use TRW\Controller\Component\SessionComponent;
use TRW\Request\RequestAggregate;
use App\Controller\PagesController;

class ComponentTest extends PHPUnit_Framework_TestCase {


	public function testMockComponent(){
		$session = new MockComponent(
			new PagesController(new RequestAggregate()));

	}


}
