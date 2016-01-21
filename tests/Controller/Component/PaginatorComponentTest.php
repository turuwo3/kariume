<?php
require '../../vendor/autoload.php';


use TRW\Controller\Component\AuthComponent;
use TRW\Request\RequestAggregate;
use TRW\Request\Query;
use TRW\Controller\Component\PaginatorComponent;

use App\Controller\UsersController;

use TRW\ActiveRecord\BaseRecord;
use TRW\ActiveRecord\Database\Driver\MySql;

class User extends BaseRecord{

}

class AuthComponentTest extends PHPUnit_Framework_TestCase {

	static $connection;

	public static function setUpBeforeClass(){
		$config = require 'db_config.php';
		self::$connection = $connection = new MySql($config);
		BaseRecord::setConnection($connection);
	}

	public function setUp(){
		$conn = self::$connection;
		$conn->query("DELETE FROM users");
		$conn->query("INSERT INTO users (id, name) VALUES
			(1, 'one'), (2, 'two'), (3, 'three'), (4, 'four'), (5, 'five'),
			(6, 'six'), (7, 'seven'), (8, 'eight'), (9, 'nine'), (10, 'ten')");
	}


	public function testPaginate(){
		$request = new RequestAggregate();
		$controller = new UsersController($request);

		$paginator = new PaginatorComponent($controller);
		$paginator->initialize('User', [
			'limit'=>1,
			'order'=>'id DESC'
		]);
		$request->setRequest(new Query(['page'=>1]));

		$limit1IdDesc = $paginator->paginate();
		
		$this->assertEquals(1, count($limit1IdDesc));
		$this->assertEquals('ten', $limit1IdDesc[0]->name);

		
		
		$request->setRequest(new Query(['page'=>10]));

	//	$notfound = $paginator->paginate();
		
	//	$this->assertEquals(1, count($notfound));
	//	$this->assertEquals('error', $notfound[0]->name);


		
		$request->setRequest(new Query(['page'=>1]));

		$id = 1;
		$where = $paginator->paginate([
			'where'=>[
				'field'=>'id',
				'comparision'=>'=',
				'value'=>$id
			]
		]);
		
		$this->assertEquals(1, count($where));
		$this->assertEquals('one', $where[0]->name);
				
		
	}







	
	


}
