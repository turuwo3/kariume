<?php
namespace TRW\Exception;

use Exception;

class ForbiddenException extends Exception{

	public function __construct($message, $code = 404){
		parent::__construct($message, $code);
	}

}
