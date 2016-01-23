<?php
namespace TRW\Controller;

use ReflectionMethod;
use TRW\Exception\MissingComponentException;
use TRW\Core\App;
use TRW\Router\Router;
use TRW\Util\Inflector;
use TRW\View\ViewInterface;
use TRW\View\View;
use TRW\View\ViewAdapter;
use TRW\Controller\ComponentCollection;

class Controller {

	private $name;
	private $request;
	private $modelName;
	private $modelClass;
	private $viewVars;
	private $view;

	public function __construct($request){
		$name = explode('\\', get_class($this));
		$this->name = substr(array_pop($name), 0, -10);
		$this->modelName = Inflector::singular($this->name);
		$this->request = $request;
		
		$viewPath = App::path('view');
		$viewDir = $this->name;
		$layoutPath = App::path('layout');
		
		$this->view = new ViewAdapter($viewPath.'/'.$viewDir, $layoutPath, $this);
	}

	public function initialize(){

	}

	public function getRequest(){
		return $this->request;
	}

	public function invokeAction($request){
		$action = $request->getParam('action');
		$method = new ReflectionMethod($this, $action);
		return $method->invokeArgs($this, $request->getParam('arguments'));
	}

	public function loadComponent($name, $config = null){
		$class = App::className($name . 'Component', 'Controller\\Component');
		if($class === false){
			throw new MissingComponentException('component not found ' . $name);
		}
		$this->{$name} = new $class($this, $config);
	}

	public function set($vars){
		$this->viewVars = $vars;
	}

	public function getViewVars(){
		return $this->viewVars;
	}

	public function render($viewFileName){

		$this->view->setViewVars($this->viewVars);
		return $this->view->render($viewFileName);
	}

	public function redirect($param){
		$url = Router::normalize($param);
		header('Location:' . $url);	
		exit;		
	}

	public function getComponent($name){
		if(isset($this->{$name})){
			return $this->{$name};
		}
		return false;
	}

	public function fileExists($fileName){
		if($this->view->getViewFile($fileName) !== false)	{
			return true;
		}
		return false;
	}
	
	public function startupProcess(){
		$this->initialize();
		$this->beforeFilter();		
	}

	public function shutdownProcess(){
		$this->afterFilter();		
	}

	public function beforeFilter(){

	}

	public function afterFilter(){}

}
