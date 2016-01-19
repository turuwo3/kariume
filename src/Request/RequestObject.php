<?php
namespace TRW\Request;

use TRW\Request\Request;

abstract class RequestObject implements Request {

	private $data;
	

	public function __construct($requestType){
		$this->setRequest($requestType);	
	}

	private function setRequest($requestType){
		foreach($requestType as $key => $value){
			$this->data[$key] = $value;
		}
	}

	public function has($key){
		if(empty($this->data)){ return false;}

		if(array_key_exists($key, $this->data)){
				return true;
		}else{
				return false;
		}
	}

	public function data($key = null){
		if($this->has($key)){
			return $this->data[$key];
		}else{
			return $this->data;
		}
	}


}
