<?php
require '../../vendor/autoload.php';

use TRW\View\Helper\PaginatorHelper;
use TRW\Request\RequestAggregate;
use TRW\Request\Query;
use TRW\Request\Param;
use TRW\ActiveRecord\Database\Driver\MySql;
use TRW\View\ViewAdapter;

class UsersController extends TRW\Controller\Controller {
	public function initialize(){
		$this->loadComponent('Paginator');
		$this->Paginator->initialize('User', [
			'limit'=>2
		]);
	}
}

class User extends  TRW\ActiveRecord\BaseRecord{

}

class PaginatorTest extends PHPUnit_Framework_TestCase {

	public function setUp(){
		$config = require '../../Controller/Component/db_config.php';
		$conn = new MySql($config);
		TRW\ActiveRecord\BaseRecord::setConnection($conn);
		$conn->query("DELETE FROM users");
		$conn->query("INSERT INTO users(id, name) VALUES
			(1, 'one'), (2, 'two'), (3, 'three'), (4, 'four'), (5, 'five')");
	}

	public function testPrevCurrentNext(){
		$request = new RequestAggregate();
		$controller = new UsersController($request);
		$controller->initialize();
		$request->setRequest(new Param('/Users/page'));



		$request->setRequest(new Query([]));		
		$controller->Paginator->paginate();
		$view = new ViewAdapter('../../app/View', '../../app/Layout', $controller);
		//current = 1
		$this->assertEquals(null, $view->Paginator->prev('prev'));
		$this->assertEquals(1, $view->Paginator->current());
		$this->assertEquals("<a href='/Users/page/?page=2'>next</a>",
			 $view->Paginator->next('next'));



		$request->setRequest(new Query(['page'=>1]));
		$controller->Paginator->paginate();
		//current = 1
		$this->assertEquals(null, $view->Paginator->prev('prev'));
		$this->assertEquals(1, $view->Paginator->current());
		$this->assertEquals("<a href='/Users/page/?page=2'>next</a>",
			 $view->Paginator->next('next'));
		
		
		
		$request->setRequest(new Query(['page'=>2]));
		$controller->Paginator->paginate();
		//current = 1
		$this->assertEquals("<a href='/Users/page/?page=1'>prev</a>",
			 $view->Paginator->prev('prev'));
		$this->assertEquals(2, $view->Paginator->current());
		$this->assertEquals(null,
			 $view->Paginator->next('next'));
	}


}
