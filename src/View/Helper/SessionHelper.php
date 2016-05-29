<?php
namespace TRW\View\Helper;

use TRW\View\Helper;

/**
* Sessionのメッセージを表示するクラス.
*
*
*/
class SessionHelper extends Helper {

/**
* セッションオブジェクト.
*
* @var \TRW\Request\Session
*/
	private $session;

	public function __construct($view){
		$this->session = $view->getController()->getRequest()->getSession();	
	}

/**
* フラッシュメッセージを返す.
*
* 一度取得されるとメッセージは消去される
*
* @return sring
*/
	public function flash(){
		$message = $this->session->read('Flash');

		$this->session->write('Flash', null);

		return $message;
	}

}
