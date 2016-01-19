<?php
namespace App\Controller;

use App\Model\User;

class UsersController extends AppController{

	public function index($id){
		$user = User::read(1);
		$this->set(['var'=>$user->name]);
	}

}
