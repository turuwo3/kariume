<?php

namespace TRW\View;

use TRW\View\ViewBlock; 

class View {

	private $extension = '.tmpl';

	private $layoutPath;
	private $layoutFile = 'default';

	private $viewPath;

	private $viewBlock;

	private $viewVars = [];

	public function __construct($viewPath, $layoutPath, $viewVars = array()){
		$this->layoutPath = $layoutPath;
		$this->viewPath = $viewPath;
		$this->viewVars = $viewVars;
		$this->viewBlock = new ViewBlock();
	}



	public function getLayoutFile($fileName){
		$file = $this->layoutPath . '/' . $fileName . $this->extension;
		if(is_file($file)){
				return $file;
		}
		return false;
	}

	public function getViewFile($fileName){
		$file = $this->viewPath . '/' . $fileName . $this->extension;
		if(is_file($file)){
				return $file;
		}
		return false;
	}

	public function setViewVars($vars){
		if($vars === null){
			$vars = [];
		}
		$this->viewVars = array_merge($this->viewVars, $vars);
	}

	public function render($file){
		$data = $this->viewVars;
		$viewFile = $this->getViewFile($file);
		$viewContent = $this->deploymentVars($viewFile, $data);
		$this->viewBlock->set('content', $viewContent);

		$layoutFile = $this->getLayoutFile($this->layoutFile);
		$layoutContent = $this->deploymentVars($layoutFile, $data);
		$this->viewBlock->set('content', $layoutContent);

		return $this->viewBlock->get('content');
	}

	public function assign($name, $value){
		$this->viewBlock->set($name, $value);
	}

	public function fetch($name){
		return $this->viewBlock->get($name);
	}

	private function deploymentVars($file, $vars = array()){
			extract($vars);
			ob_start();
			require $file;
			return ob_get_clean();
	}

}