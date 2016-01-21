<?php
namespace TRW\View\Helper;

use TRW\View\Helper;

class PaginatorHelper  extends Helper{

	private $veiw;

	private $controller;

	private $request;

	private $component;

	private $current;

	public function __construct($view){
		$this->view = $view;
		$this->controller = $view->getController();
		$this->request = $this->controller->getRequest();
		$this->component = $this->controller->getComponent('Paginator');
	}

	public function current(){
		$current = $this->component->getCurrent();
		
		if($current === 0){
			return 1;
		}

		return $current;
	}

	public function next($word){
		if(!$this->component->isNext()){
			return null;
		}

		$next = $this->current() + 1;
		$query = "?page={$next}";

		return $this->link($query, $word);
	}

	public function prev($word){
		if(!$this->component->isPrev()){
			return null;
		}
		$prev = $this->current() - 1;
		$query = "?page={$prev}";

		return $this->link($query, $word);	
	}

	private function link($query, $word){
		$request = $this->request;
		$controller = $request->getParam('controller');
		$action = $request->getParam('action');
		$args = implode('/', $request->getParam('arguments'));
		$link = "<a href='/{$controller}/{$action}/{$args}/{$query}'>{$word}</a>";

		return $link;
	}

}
