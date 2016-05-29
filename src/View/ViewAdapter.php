<?php
namespace TRW\View;

use Exception;
use TRW\Core\App;
/**
* VIewをラップしたクラス
*
*
*/
class ViewAdapter extends View implements ViewInterface {

/**
* コントローラーのインスタンス.
*
* @var \TRW\Controller\Controller
*/
	private $controller;

	public function __construct($viewPath, $layoutPath, $elementPath, $controller){
		parent::__construct($viewPath, $layoutPath, $elementPath);
		$this->controller = $controller;
	}

	public function __get($name){
		if(isset($this->{$name})){
			return $this->{$name};
		}
		return $this->loadHelper($name);
	}

/**
* 自身の保持しているコントローラーを返す.
*
* @return \TRW\Controller\Controller
*/
	public function getController(){
		return $this->controller;
	}

/**
* ヘルパークラスをロードする.
*
* @return \TRW\View\Helper
*/
	public function loadHelper($name){
		$class = App::className($name . 'Helper', 'View\\Helper');

		if($class === false){
			throw new Exception('missing helper ' . $name);
		}

		return $this->{$name} = new $class($this);
	}

	public function assign($name, $value){
		parent::assign($name, $valur);
	}

	public function fetch($name){
		return parent::fetch($name);
	}

	public function setViewVars($vars){
		return parent::setViewVars($vars);
	}

	public function render($file){
		return parent::render($file);
	}

}
