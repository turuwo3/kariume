<?php
namespace TRW\Exception;

use Exception;
use TRW\Request\RequestAggregate;
use TRW\Controller\ErrorsController;
use TRW\Core\Configure;

class ExceptionRenderer {

	private $error;

	private $controller;

	private $template;

	private $method;

	public function __construct(Exception $e){
		$this->error = $e;
		$this->method = $this->template = str_replace('Exception', get_class($e), '');
		$this->loadController();

		if(!method_exists($this, $this->method)){
			$this->method = 'error';		
		}

	}

	public function render(){
		call_user_func_array([$this, $this->method], [$this->error]);
	}

	private function loadController(){
		$request = new RequestAggregate();
		$controller = new ErrorsController($request);
		$controller->initialize();
		
		$this->controller = $controller;
		$this->controller->set(['error'=>$this->error]);
	}

	public function error(){
		if(Configure::read('Debug', 'level') === 1){
			$fileName = 'develop';
		}else {
			$fileName = 'error';
		}
		echo $this->controller->render($fileName);
	}



}
