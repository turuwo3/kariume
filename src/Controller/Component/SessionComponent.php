<?php
namespace TRW\Controller\Component;

use TRW\Controller\Component;

class SessionComponent extends Component {

	private $session;

	public function __construct($controller){
		$this->session = $controller->getRequest()->getSession();		
	}

	public function setFlash($value){
		$this->session->write('Flash', $value); 
	}


}
