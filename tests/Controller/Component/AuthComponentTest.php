<?php
require '../../vendor/autoload.php';


use TRW\Controller\Component\AuthComponent;
use TRW\Request\RequestAggregate;
use TRW\Request\Post;
use App\Controller\UsersController;

class AuthComponentTest extends PHPUnit_Framework_TestCase {

	public function testAuthentication(){
		$request = new RequestAggregate(new Post(['name'=>'aaa','password'=>'aaa']));
		$controller = new UsersController($request);
		$controller->loadComponent('Auth');

		

	}


	public function testPassword(){
		$auth = new AuthComponent(new UsersController(new RequestAggregate()));	
		
		$pwSuccess = $auth->password('pass');

		$this->assertNotEquals(false, $pwSuccess);


		$pwFalse = $auth->password(null);

		$this->assertEquals(false, $pwFalse);


		$pwFalse2 = $auth->password('');

		$this->assertEquals(false, $pwFalse2);
	}


}
