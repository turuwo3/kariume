<?php
namespace TRW\Controller;

use ReflectionMethod;
use Exception;
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


	public function __construct($request){
		$name = explode('\\', get_class($this));
		$this->name = substr(array_pop($name), 0, -10);
		$this->modelName = Inflector::singular($this->name);
		$this->request = $request;
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
			throw new Exception('component not found ' . $name);
		}
		$this->{$name} = new $class($this, $config);
	}

	public function set($vars){
		$this->viewVars = $vars;
	}

	public function getViewVars(){
		return $this->viewVars;
	}

	public function render($request){
		$viewPath = App::path('view');
		$layoutPath = App::path('layout');
		$view = new ViewAdapter($viewPath, $layoutPath, $this);

		$viewDir = $this->name;
		$viewFileName = $request->getParam('action');

		$view->setViewVars($this->viewVars);

		return $view->render($viewDir . '/' .$viewFileName);
	}

	public function redirect($param){
		$url = Router::normalize($param);
		header('Location:' . $url);	
		exit;		
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
