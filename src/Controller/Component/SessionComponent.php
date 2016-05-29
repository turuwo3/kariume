<?php
namespace TRW\Controller\Component;

use TRW\Controller\Component;

/**
* セッションを管理するクラス.
*
*
*/
class SessionComponent extends Component {

/**
* セッションオブジェクト.
*
* @var \TRW\Request\Session
*/
	private $session;

	public function __construct($controller){
		$this->session = $controller->getRequest()->getSession();		
	}

/**
* フラッシュメッセージを格納する.
*
* @return void
*/
	public function setFlash($value){
		$this->session->write('Flash', $value); 
	}


}
