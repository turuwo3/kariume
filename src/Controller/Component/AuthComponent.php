<?php
namespace TRW\Controller\Component;

use Exception;
use TRW\Core\App;
use TRW\Exception\ForbiddenException;
use TRW\Exception\MissingModelException;
use TRW\Controller\Component;
/**
* 認証を行うクラス
*
*
*
*/
class AuthComponent extends Component {
/*
* セッション
*
* @var \TRW\Request\Session
*/
	private $session;

/**
* 認証したユーザーにアクセスするためのキー.
*
* @var string
*/
	private static $sessionKey = 'Auth.User';


/**
* 認証されていないユーザーへのアクションの許可.
*
* @var array 
* $arrowAction = 
*  [
*    'index', 'view' 
*  ];
*/
	private $allowActions = [];

/**
* ログイン後、ログアウト後のリダイレクト先.
* 
* @var array 
* $redirect = 
*  [
*    'logninRwderect' => ['controller'=>'Users', 'action'=>'edit'],
*    'logoutRedirect' => ['controller'=>'Users', 'action'=>'index']
*  ];
*/
	private $redirect = [];

/**
* コントローラーオブジェクト.
*
* @var TRW\Controller\Controller
*/
	private $controller;

	public function __construct($controller){
		$this->controller = $controller;
		$this->session = $controller->getRequest()->getSession();
	}

/**
* 認証チェックを行う.
*
* ControllerのbeforeFilterで使用してください
*
* @param \TRW\Controller\Controller コントローラーオブジェクト
* @return boolean
*/
	public function startup($controller){
		if(!$this->authorizedAction($controller)){
			return true;
		}
		return false;
	}

/**
* 認証チェックを行う.
*
* @param \TRW\Controller\Controller
* @return boolean
* @throws \TRW\Exception\ForbiddenException 認証されていない時,
* 許可されていないメソッドの実行で
*/
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

/**
* 認証されたユーザーを返す.
*
* @return \TRW\ActiveRecord\BaseRecord|boolean BaseRecordを継承したUserモデル
* 見つからなければfalse
*/
	public function user(){
		return $this->session->read(self::$sessionKey);
	}

/**
* 送られてきたリクエストデータから認証を行う.
*
* @return \TRW\ActiveRecord\BaseRecord|booean BaseRecordを継承したUserモデル,
* 認証に市失敗するとfalse
*
*/
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

/**
* パスワードをハッシュ化する.
*
* @param string $pasword　任意の文字列
* @return ハッシュ化されたパスワード
*/
	public function password($password){
		if($password === null || !is_string($password) || $password === ''){
			return false;
		}

		$salt = App::securitySalt();

		return crypt($password, $salt);
	}

/**
* 認証されていないユーザーへのアクションの許可を行う.
*
* @aparam array $allowActions
*/
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

/**
* ログインする.
*
* 認証に成功すればユーザーをSessionに保持する.
*
* @return boolean　認証に成功スるとtrue 失敗するとfalse
*/
	public function login(){
		$user = $this->authenticate();
		if($user !== false){
			$this->session->write(self::$sessionKey, $user);
			return true;
		}
		return false;
	}

/**
* ログアウトする.
*
* 
*/
	public function logout(){
		$this->session->delete(self::$sessionKey);
	}


}
