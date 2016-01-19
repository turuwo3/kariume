<?php
namespace TRW\Request;

use TRW\Request\Post;
use TRW\Request\Query;
use TRW\Request\Param;
use TRW\Request\Session;

final class RequestAggregate {

	private $post;
	private $query;
	private $param;
	private $session;

	public function __construct($requests = array()){
		foreach($requests as $val){
			$this->setRequest($val);		
		}
		$this->session = new Session;
	}

	public static function createFromGlobals(){
		return new RequestAggregate(
			[
				new Post($_POST),
				new Query($_GET),
				new Param($_SERVER['REQUEST_URI'])
			]
		);
	}

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

	public function is($type){
		return $_SERVER['REQUEST_METHOD'] === $type;
	}

	public function getPost($key = null){
		return $this->post->data($key);
	}

	public function getQuery($key = null){
		return $this->query->data($key);
	}

	public function getParam($key = null){
		return $this->param->data($key);
	}

	public function getSession(){
		return $this->session;
	}

}
