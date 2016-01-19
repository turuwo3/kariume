<?php
namespace TRW\View;

class ViewBlock {

	private $blocks = array();

	public function set($key, $value){
		$this->blocks[$key] = $value;
	}

	public function get($key){
		return $this->blocks[$key];
	}

}
