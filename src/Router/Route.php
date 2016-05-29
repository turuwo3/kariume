<?php
namespace TRW\Router;

use TRW\Request\Param;
/**
* 任意のパスに対するパラメータを表すクラス
*
*
*/
class Route {

/**
* URLパラメータに対するパス.
*
* @var string
*/
	private $path;

/**
* パスに対するパラメーター.
*
* @var array
* @param = 
*  [
*    'controller' => 'Users'
*    'action' => 'view',
*    'arguments' => [
*      1,'bar'  
*    ]
*  ]
*/	
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

/**
* パスを取得する.
*
* @return string
*/
	public function getPath(){
		return $this->path;
	}

/**
* パラメータを取得する.
*
* @return array
*/
	public function getParam(){
		
		return $this->param;
	}

/**
* URLパラメータを生成する.
*
* @return \TRW\Request\Param
*/
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
