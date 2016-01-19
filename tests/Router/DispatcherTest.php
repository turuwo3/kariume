<?php

require '../vendor/autoload.php';

use TRW\Core\Configure;
use TRW\Router\Dispatcher;
use TRW\Router\Router;
use TRW\Request\RequestAggregate;
use TRW\Request\Param;

class DispatcherTest extends PHPUnit_Framework_TestCase {

	public function setUp(){
		Configure::load('../app/config/config.php');
		Router::clear();
	}

	public function testCreateController(){
		$request = new RequestAggregate();
		$request->setRequest(new Param('/Users/index/1'));
		$dispatcher = new Dispatcher($request);

		$controller1 = $dispatcher->getController($request);

		$this->assertInstanceOf('App\Controller\UsersController', $controller1);



		$request->setRequest(new Param(''));
		Router::add('/', ['controller'=>'Pages', 'action'=>'index', 'arguments'=>[1]]);
		$param = Router::parse($request);
		$request->setRequest($param);
		$controller2 = $dispatcher->getController($request);

		$this->assertInstanceOf('App\Controller\PagesController', $controller2);		
	}

	public function testDispatch(){
		$request = new RequestAggregate();
		$request->setRequest(new Param(''));
		Router::add('/', ['controller'=>'Pages', 'action'=>'index', 'arguments'=>[1]]);
		$dispatcher = new Dispatcher($request);

		$expect = '<div><p>1</p>
</div>
';
		$this->expectOutputString($expect);

		$dispatcher->dispatch($request);

	}



}
















