<?php
require '../../vendor/autoload.php';

use TRW\Request\RequestAggregate;
use TRW\Request\Post;
use TRW\Request\Query;
use TRW\Request\Param;

class RequestAggregateTest extends PHPUnit_Framework_TestCase {

	public function testCreateFromGrobals(){
		$_SERVER['REQUEST_URI'] = '/users/index/1';

		$request = RequestAggregate::createFromGlobals();
		print_r($request);
	}



}
