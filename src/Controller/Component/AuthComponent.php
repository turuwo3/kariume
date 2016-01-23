<?php
namespace TRW\Controller\Component;

use Exception;
use TRW\Core\App;
use TRW\Exception\ForbiddenException;
use TRW\Exception\MissingModelException;
use TRW\Controller\Component;

class AuthComponent extends Component {

	private $session;

	private static $sessionKey = 'Auth.User';

	private $user;

	private $allowActions = [];

	private $redirect = [];

	private $controller;

	public function __construct($controller){
		$this->controller = $controller;
		$this->session = $controller->getRequest()->getSession();
	}

	public function startup($controller){
		if(!$this->authorizedAction($controller)){
			return true;
		}
		return false;
	}

	private function authorizedAction($controller){
		$user = $this->user();
		$action = $controller->getRequest()->getParam('action');

		if($user){
			return true;
		}

		if(in_array($action, $this->allowActions)){
			return true;
		}

		throw new ForbiddenException('forebedden');;
	}


	public function user(){
		return $this->session->read(self::$sessionKey);
	}

	private function authenticate(){
		$model = App::className('User', 'Model');
		if($model === false){
			throw new MissingModelException('User model notfound');
		}

		$request = $this->controller->getRequest();
		$userName = $request->getPost('name');
		$password = $this->password($request->getPost('password'));

		$result = $model::whereAll($model::tableName(), [
			'where' => [
				'field' => 'name',
				'comparision' => '=',
				'value' => $userName
			],
			'and'=>[
				'field' => 'password',
				'comparision' => '=',
				'value' => $password
			]
		], $model);
		
		if(count($result) === 0){
			return false;
		}
		
		$user = $result[0];

		return $user;
	}

	public function password($password){
		if($password === null || !is_string($password) || $password === ''){
			return false;
		}

		$salt = App::securitySalt();

		return crypt($password, $salt);
	}

	public function allow(array $allowActions){
		foreach($allowActions as $action){
			$this->allowActions[] = $action;
		}
	}

	public function loginRedirect($param = null){
		if($param === null){
			return $this->redirect['loginRedirect'];
		}
		$this->redirect['loginRedirect'] = $param;
	}

	public function logoutRedirect($param = null){
		if($param === null){
			return $this->redirect['logoutRedirect'];
		}
		$this->redirect['logoutRedirect'] =  $param;
	}

	public function login(){
		$user = $this->authenticate();
		if($user !== false){
			$this->session->write(self::$sessionKey, $user);
			return true;
		}
		return false;
	}

	public function logout(){
		$this->session->delete(self::$sessionKey);
	}


}
