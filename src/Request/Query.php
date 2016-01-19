<?php
namespace TRW\Request;

use TRW\Request\RequestObject;

class Query extends RequestObject {

	public function __construct($requestType){
		parent::__construct($requestType);
	}

}
