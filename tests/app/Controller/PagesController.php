<?php
namespace App\Controller;

class PagesController extends AppController {

	public function initialize(){
		$this->loadComponent('Mock');
	}

	public function index($id){
		$this->set(['var'=>$id]);
	}

}
