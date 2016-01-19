<?php
require '../vendor/autoload.php';

use TRW\View\ViewAdapter;
use TRW\Request\RequestAggregate;
use TRW\Core\Configure;
use App\Controller\UsersController;

class ViewAdapterTest extends PHPUnit_Framework_TestCase {

	protected $view;

	public function setUp(){
		Configure::load('../app/config/config.php');
		$viewPath = '../app/View';
		$layoutPath = '../app/Layout';
		$this->view = new ViewAdapter(
			$viewPath,
			$layoutPath,
			new UsersController(new RequestAggregate())
			);
	}

	public function testloadHelper(){
		$this->assertInstanceOf('TRW\View\Helper\SessionHelper', $this->view->Session);
	}



}
