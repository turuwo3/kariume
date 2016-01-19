<?php
require '../vendor/autoload.php';

use TRW\Router\Router;
use TRW\Request\RequestAggregate;
use TRW\Request\Param;

class RouterTest extends PHPUnit_Framework_TestCase {


	public function testAddAndRemoveAndGet(){
		Router::add('/', ['controller' => 'Pages', 'action' => 'index', 'arguments'=>[1]]);

		$this->assertEquals(['controller'=>'Pages', 'action'=>'index', 'arguments'=>[1]],
			Router::get('/')->getParam());


		Router::add('/Articles', ['controller'=>'Articles', 'action'=>'index']);

		$this->assertEquals(['controller'=>'Articles', 'action'=>'index', 'arguments'=>[]],
			Router::get('/Articles')->getParam());

		$this->assertEquals(false, Router::get('*'));
	}

	public function testParse(){
		$param = new Param('/Articles');
		$request = new RequestAggregate([$param]);

		$this->assertEquals(true, Router::has($request->getParam('url')));
		$this->assertEquals('Articles', Router::parse($request)->data('controller'));
		$this->assertEquals('index', Router::parse($request)->data('action'));
		$this->assertEquals([], Router::parse($request)->data('arguments'));
	}

	public function testNormalize(){
		$param1 = [
			'controller'=>'Users',
			'action'=>'index',
			'arguments'=>[
				0,1
			]
		];

		$url1 = Router::normalize($param1);

		$this->assertEquals('/Users/index/0/1', $url1);


		$param2 = [
			'controller'=>'Users',
			'action'=>'index',
		];

		$url2 = Router::normalize($param2);

		$this->assertEquals('/Users/index/', $url2);
	}

}
