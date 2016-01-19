<?php
namespace TRW\Router;

use TRW\Request\Param;

class Route {

	private $path;
	private $param;

	public function __construct($path, $param){
		$this->path = $path;
		$merge = [
			'controller'=>'',
			'action'=>'',
			'arguments'=>[]
		];
		$this->param = array_merge($merge, $param);
	}

	public function getPath(){
		return $this->path;
	}

	public function getParam(){
		
		return $this->param;
	}

	public function parse(){
		$param = $this->param;

		$url = '/' . implode('/', [
			$param['controller'],
			$param['action'],
			implode('/', $param['arguments'])
		]);

		return new Param($url);
	}

}
