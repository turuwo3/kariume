<?php
namespace TRW\View\Helper;

use TRW\View\Helper;

class SessionHelper extends Helper {

	private $session;

	public function __construct($view){
		$this->session = $view->getController()->getRequest()->getSession();	
	}

	public function flash(){
		$message = $this->session->read('Flash');

		$this->session->write('Flash', null);

		return $message;
	}

}
