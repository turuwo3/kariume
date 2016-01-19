<?php

require '../vendor/autoload.php';

use TRW\Core\Configure;
use TRW\Core\App;
use TRW\Request\RequestAggregate;
use TRW\Request\Param;
use App\Controller\UsersController;
use App\Controller\PagesController;
use App\Controller\CommentsController;
use TRW\ActiveRecord\BaseRecord;
use TRW\ActiveRecord\Database\Driver\MySql;

class ControllerTest extends PHPUnit_Framework_TestCase {

	public function setUp(){
		Configure::load('../app/config/config.php');
		BaseRecord::setConnection(new MySql(Configure::read('Database', 'MySql')));		
	}
/*
	public function testRender(){
		$request = new RequestAggregate();
		$request->setRequest(new Param('/Users/index/1'));
		$controller = new UsersController($request);
		$controller->invokeAction($request);

		$output = $controller->render($request);
		$expect = '<div><p>bar</p>
</div>
';
		$this->expectOutputString($expect);
		echo $output;	
	}
*/
/*
	public function testSetFlash(){
		$request = new RequestAggregate();
		$request->setRequest(new Param('/Users/index/1'));
		$controller = new PagesController($request);
		$controller->invokeAction($request);

		$controller->Session->setFlash('flash');
		$output = $controller->render($request);
		$expect = '<div><p>bar</p>
</div>
';
		$this->expectOutputString($expect);
		echo $output;	
	}
*/

	public function testLoadComponent(){	
		$request = new RequestAggregate();
		$request->setRequest(new Param('/Users/index/1'));
		$controller = new UsersController($request);

		$this->assertInstanceOf('App\Controller\Component\MockComponent', $controller->Mock);
//		$this->assertInstanceOf('TRW\Controller\Component\SessionComponent', $controller->Session);
	}




}
