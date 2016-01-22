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
		$this->method = $this->template = $this->getName($e);
		$this->loadController();

		if(!method_exists($this, $this->method)){
			$this->method = 'error';		
		}

	}

	private function getName($object){
		$fullName = get_class($object);
		$explode = explode("\\", $fullName);
		$class = array_pop($explode);
		$name = str_replace('Exception', '',$class);
		return $name;
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
		$fileName = $this->template;
		if(Configure::read('Debug', 'level') === 1){
			$fileName = 'develop';
		}
		if(!$this->controller->fileExists($fileName)){
			$fileName = 'error';
		}

		echo $this->controller->render($fileName);
	}



}
