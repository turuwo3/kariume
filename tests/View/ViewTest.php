<?php

require '../vendor/autoload.php';

use TRW\View\View;

class ViewTest extends PHPUnit_Framework_TestCase {

	protected $view;

	public function setUp(){
		$viewPath = '../app/View';
		$layoutPath = '../app/Layout';
		$this->view = new View($viewPath, $layoutPath);
	}

	public function testRender(){
		$fileName = 'Users/index';
		$viewVars = ['var'=>'view'];
		$this->view->setViewVars($viewVars);
		$output = $this->view->render($fileName);

		$expect ='<div><p>view</p>
</div>
';

		$this->expectOutputString($expect);	
		echo $output;
	}

	public function testRender2(){
		$fileName = 'Users/test';
		$viewVars = null;
		$this->view->setViewVars($viewVars);
		$output = $this->view->render($fileName);

		$expect ='<div><p>no vars</p>
</div>
';

		$this->expectOutputString($expect);	
		echo $output;
	}


}
