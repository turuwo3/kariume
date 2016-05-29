<?php
namespace TRW\Exception;

use Exception;
use TRW\Request\RequestAggregate;
use TRW\Controller\ErrorsController;
use TRW\Core\Configure;

/**
* 例外発生時、適切な例外用ページをレンダリングするクラス.
*
*
*
*/
class ExceptionRenderer {

/**
* 発生した例外クラス.
*
* @var Exception
*/
	protected $error;

/**
* 適切な例外ページをレンダリングするコントローターオブジェクト.
*
* @var \TRW\Controller\ErrorController
*/
	protected $controller;

/**
* レンダリングするテンプレートファイル名.
*
* @var staing
*/
	protected $template;

/**
* 例外発生時実行するメソッド名.
*
* 例外発生時、それに対応する独自のメソッドを実行したい時は
* このクラスを継承してメソッドを実装する
* メソッド名は発生した例外クラスから'Exception'を除去した名前にしなければならない 
*
* ( 例
* 発生した例外クラス名 MissingModelException
* それに対応するメソッド名 missingModel
* 
* 対応するメソッドがない場合はメソッド名は'error'になる
*
* @var string
*/
	private $method;

	public function __construct(Exception $e){
		$this->error = $e;
		$this->method = $this->template = $this->getName($e);
		$this->loadController();

	}

/**
* 例外の名前を取得する.
*
* @return string
*/
	private function getName($object){
		$fullName = get_class($object);
		$name = 'error';
		if(strpos($fullName, "\\") !== false){
			$explode = explode("\\", $fullName);
			$class = array_pop($explode);
			$name = str_replace('Exception', '',$class);
		}

		return $name;
	}

/**
* 自身で実装した例外に対応するメソッドを呼ぶ.
*
* このメソッドは\TRW\Exception\ErrorHandlerによって
* 例外発生時に呼ばれる
*
* 実装したメソッドがない場合はこのクラスのerrorメソッドを呼ぶ
*/
	public function callUserFunction(){
		if(method_exists($this, $this->method)){
			return call_user_func_array([$this, $this->method], [$this->error]);
		}
		return call_user_func_array([$this, 'error'], [$this->error]);
	}


/**
* ファイルをレンダリングする.
*
* @return void
*/
	protected function render($fileName){
		echo $this->controller->render($fileName);
	}

/**
* 例外をレンダリングするためのコントローラーをロード.
*
* @return void
*/
	private function loadController(){
		$request = new RequestAggregate();
		$controller = new ErrorsController($request);
		$controller->initialize();
		
		$this->controller = $controller;
		$this->controller->set(['error'=>$this->error]);
	}

/**
* デフォルトのレンダリングメソッド.
*
* 独自のレンダリングメソッドを実装しなかった場合このメソッドが呼ばれる
*\
* @return void
*/
	private function error(){
		$fileName = $this->template;
		if(Configure::read('Debug', 'level') === 1){
			$fileName = 'develop';
		}

		echo $this->controller->render($fileName);
	}



}
