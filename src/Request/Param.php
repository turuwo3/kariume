<?php
namespace TRW\Request;

use TRW\Request\RequestObject;

class Param extends RequestObject {

	private $param = [
		'url' => '',
		'controller' => '',
		'action' => '',
		'arguments' => []
	];

	public function __construct($requestType){
		$this->createParam($requestType);		
	}
/*
*	@Override
*/
	public function has($key){
		if(empty($this->param)){ return false;}

		if(array_key_exists($key, $this->param)){
				return true;
		}else{
				return false;
		}
	}
/*
* @Override
*/
	public function data($key = null){
		if($this->has($key)){
			return $this->param[$key];
		}else{
			return $this->param;
		}
	}


	private function createParam($uri){

		$this->param['url'] = $uri;

		$regex = '/~\//';
		$replacedUri = preg_replace($regex, '', $uri);
		
		$newUri = explode('/', $replacedUri);
		array_shift($newUri);
		
		$param = $this->removeQueryUri($newUri);

		$param = $this->mergeParam($param);
		
		$args = $this->getArgs($param);
			
		$newParam = $this->removeArgs($param);

		$newParam['arguments'] = $args;

		$ret = $this->mergeParam($newParam);

		$this->param = array_merge($this->param, $ret);
	}

	private function removeQueryUri($uri){
		$ret = array();
		foreach($uri as $val){
			if(!preg_match('/^\?/', $val)){
				$ret[] = $val;
			}
		}
		return $ret;
	}

	private function mergeParam($param){
		$merge = array();

		if(array_key_exists(0, $param)){
			$merge['controller'] = ucfirst($param[0]);
		}

		if(array_key_exists(1, $param)){
			$merge['action'] = $param[1];
		}

		return array_merge($param, $merge);
	}

	private function getArgs($param){
		$ret = array();
		foreach($param as $val){
			if(preg_match('/^[0-9]/', $val)){
				$ret[] = $val;
			}
		}
		return $ret;
	}

	private function removeArgs($param){
		foreach($param as $key => $val){
			if(preg_match('/^[0-9]/', $val) ){
				unset($param[$key]);
			}
		}
		return $param;
	}

}
