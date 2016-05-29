<?php
namespace TRW\View;

/**
* ビューのバッファを保持するクラス.
*
*
*/
class ViewBlock {

	private $blocks = array();

	public function set($key, $value){
		$this->blocks[$key] = $value;
	}

	public function get($key){
		return $this->blocks[$key];
	}

}
