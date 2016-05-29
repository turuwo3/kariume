<?php
namespace TRW\Request;

use TRW\Request\Request;

/**
* リクエストクラスの基底クラス.
* 
* \TRW\Request\Post, \TRW\Request\Quesry, \TRW\Request\Paramはこのクラスを
* 継承している
*
* 
*/
abstract class RequestObject implements Request {

/**
* リクエストデータ.
*
* @var array
*/
	private $data;
	

	public function __construct($requestType){
		$this->setRequest($requestType);	
	}

/**
* リクエストデータをセットする.
*
* @param array $requestType
*/
	private function setRequest($requestType){
		foreach($requestType as $key => $value){
			$this->data[$key] = $value;
		}
	}

/**
* リクエストデータの有無を確認する.
*
*
* @param string $key 調べたいデータのキー
* @return boolean データのキーがない場合false　あればtrue
*/
	public function has($key){
		if(empty($this->data)){ return false;}

		if(array_key_exists($key, $this->data)){
				return true;
		}else{
				return false;
		}
	}

/**
* リクエストデータを取得する.
*
* @param string $key データのキー
* @return mixid データのキーがあればその値を返す
* なければすべてのデータを返す
*/
	public function data($key = null){
		if($this->has($key)){
			return $this->data[$key];
		}else{
			return $this->data;
		}
	}


}
