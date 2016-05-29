<?php
namespace TRW\Router;

use TRW\Exception\MissingControllerException;
use TRW\Core\Configure;
use TRW\Core\App;
use TRW\View\View; 
use TRW\Request\Param;

/**
* ユーザーからのリクエストから適切なコントローラーを生成するクラス.
*
*
*/
class Dispatcher {

/**
* リクエストデータから適切なコントローラを生成し、適切なメソッドを実行する.
*
* @param \TRW\Request\RequestAggreagete
* @return mixid　結果を出力する
*/
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

/**
* コントローラーのメソッドを実行する.
*
* @param \TRW\Controller\Controller
* @param \TRW\Request\RequestAggreagate
* @return void
*/
	public function invoke($controller, $request){
		$controller->startupProcess();
		$controller->invokeAction($request);
		$controller->shutdownProcess();
	}

/**
* コントローラーを生成する.
*
* @param \TRW\Requst\RequestAggreagate
* @return \TRW\Controller\Controller
* @throws \TRW\Exception\MissingControllerException
* コントローラークラスが見つからない時
*/
	public function getController($request){
		$class = $request->getParam('controller');
		$fullName = App::className($class . 'Controller', 'Controller');

		if($fullName === false){
			throw new MissingControllerException('missing controller');
		}

		$controller = new $fullName($request);

		return $controller;
	}

/**
* ビューを出力する.
*
* @param \TRW\Controller\Controller 
* @param \TRW\Request\RequestAggreagate
* @return mixid ビューの出力
*/
	public function render($controller, $request){
		$viewFileName = $request->getParam('action');
		return $controller->render($viewFileName);
	}

}
