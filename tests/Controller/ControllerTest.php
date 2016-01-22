<?php

require '../vendor/autoload.php';

use TRW\Core\Configure;
use TRW\Core\App;
use TRW\Request\RequestAggregate;
use TRW\Request\Param;
use TRW\Controller\Controller;
use TRW\Controller\Component;
use TRW\ActiveRecord\BaseRecord;
use TRW\ActiveRecord\Database\Driver\MySql;


class MockComponent extends Component {

	public function startup(){
		return 'success';
	}

}

class UsersController extends Controller{

	public function initialize(){
		$this->Mock = new MockComponent($this);
	}

	public function index($id){
		$this->set(['var'=>'bar']);
	}
			
}

class ControllerTest extends PHPUnit_Framework_TestCase {

	public function setUp(){
		Configure::load('../app/config/config.php');
		BaseRecord::setConnection(new MySql(Configure::read('Database', 'MySql')));		
	}

	public function testRender(){
		$request = new RequestAggregate();
		$request->setRequest(new Param('/Users/index/1'));
		$controller = new UsersController($request);
		$controller->invokeAction($request);

		$output = $controller->render('index');
		$expect = '<div><p>bar</p>
</div>
';
		$this->expectOutputString($expect);
		echo $output;	
	}



	public function testLoadComponent(){	
		$request = new RequestAggregate();
		$request->setRequest(new Param('/Mocks/index/1'));
		$controller = new UsersController($request);
		$controller->initialize();

		$this->assertInstanceOf('MockComponent', $controller->Mock);
		$this->assertEquals('success', $controller->Mock->startup());
	}




}
