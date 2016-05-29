<?php
namespace TRW\Request;

use TRW\Request\Post;
use TRW\Request\Query;
use TRW\Request\Param;
use TRW\Request\Session;

/**
* ユーザーからのリクエストデータ.
*
*
*
*/
final class RequestAggregate {

/**
* ポストデータ.
*
* @var \TRW\Request\Post
*/
	private $post;

/**
* ゲットデータ.
*
* @var \TRW\Request\Query
*/
	private $query;

/**
* URLパラメーター.
*
* @var \TRW\Request\Param
*/
	private $param;

/**
* セッションデータ.
*
* @var \TRW\Request\Session
*/
	private $session;

	public function __construct($requests = array()){
		foreach($requests as $val){
			$this->setRequest($val);		
		}
		$this->session = new Session;
	}

/**
* リクエストデータを作成する.
*
* @return \TRW\Request\RequestAggregate
*/
	public static function createFromGlobals(){
		return new RequestAggregate(
			[
				new Post($_POST),
				new Query($_GET),
				new Param($_SERVER['REQUEST_URI'])
			]
		);
	}

/**
* リクエストデータをセットする.
*
* @praram \TRW\Request\RequestObject
*/
	public function setRequest($requestObject){
		if(!($requestObject instanceof Request)){
			throw new InvalidArgumentException('interface Requestを実装していません');		
		}

		if($requestObject instanceof Post){
			$this->post = $requestObject;
			return true;
		}else if($requestObject instanceof Query){
			$this->query = $requestObject;
			return true;
		}else if($requestObject instanceof Param){
			$this->param = $requestObject;
			return true;
		}
		return false;
	}

/**
* リクエストタイプを判定する.
*
* @param string $type リクエストタイプ
*/
	public function is($type){
		return $_SERVER['REQUEST_METHOD'] === $type;
	}

/**
* ポストデータを取得する.
*
* @param $key 取得したいデータのキー
* @return mixid キーが見つからない場合はすべてのデータを返す
*/
	public function getPost($key = null){
		return $this->post->data($key);
	}

/**
* ゲットトデータを取得する.
*
* @param $key 取得したいデータのキー
* @return mixid キーが見つからない場合はすべてのデータを返す
*/
	public function getQuery($key = null){
		return $this->query->data($key);
	}

/**
* パラメータを取得する.
*
* @param $key 取得したいデータのキー
* @return mixid キーが見つからない場合はすべてのデータを返す
*/
	public function getParam($key = null){
		return $this->param->data($key);
	}

/**
* セッションオブジェクトを返す.
*
* @return \TRW\Request\Session
*/
	public function getSession(){
		return $this->session;
	}

}
