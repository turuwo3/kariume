<?php
namespace TRW\Router;

use Exception;
use TRW\Core\Configure;
use TRW\Core\App;
use TRW\View\View; 
use TRW\Request\Param;

class Dispatcher {

	public function dispatch($request){

		if(empty($request->getParam('controller'))){
			$defaultParam = Router::parse($request);
			$request->setRequest($defaultParam);
		}

		if(Router::has($request->getParam('url'))){
			$mapedParam = Router::parse($request);
			$request->setRequest($mapedParam);
		}

		$controllerInstance = $this->getController($request);
		$this->invoke($controllerInstance, $request);

		echo $this->render($controllerInstance, $request);
	}

	public function invoke($controller, $request){
		$controller->startupProcess();
		$controller->invokeAction($request);
		$controller->shutdownProcess();
	}

	public function getController($request){
		$class = $request->getParam('controller');
		$fullName = App::className($class . 'Controller', 'Controller');

		if($fullName === false){
			throw new Exception('missing controller');
		}

		$controller = new $fullName($request);

		return $controller;
	}

	public function render($controller, $request){
		$viewFileName = $request->getParam('action');
		return $controller->render($viewFileName);
	}

}
