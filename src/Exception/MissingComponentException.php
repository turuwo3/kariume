<?php
namespace TRW\Exception;

use Exception;

class MissingComponentException extends Exception {

	public function __construct($message, $code = 500){
			parent::__construct($message, $code);
	}

}
