<?php
namespace App\Controller;

use TRW\Controller\Controller;

class CommentsController extends Controller {

	public function index($id){
		$this->set(['var'=>$id]);
	}

}
