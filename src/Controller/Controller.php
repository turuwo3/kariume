<?php
namespace TRW\Controller;

use ReflectionMethod;
use TRW\Exception\MissingComponentException;
use TRW\Core\App;
use TRW\Router\Router;
use TRW\Util\Inflector;
use TRW\View\ViewInterface;
use TRW\View\View;
use TRW\View\ViewAdapter;
use TRW\Controller\ComponentCollection;

/**
* このクラスはユーザーからのリクエストを受け取ってモデルとビューを制御する/
*
* このクラスを継承することによってモデルとビューの制御を行うことができるようになります
*
* オーバーライト可能なメッソド、またはフィールドとコメントされいる、
* メソッド、フィールド以外のオーバーライドを禁止します
*
* またpublic, protectedなメソッド、フィールドであっても
* 使用禁止のコメントが書かれているものは使用してはいけません
*
*/
class Controller {

/**
* 自身のクラス名
*
* @var string
*/
	private $name;

/**
* ユーザーからのリクエストデータ
*
* @var TRW\Request\RequestAggregate
*/
	private $request;

/**
* ビューに送るためのデータ.
*
* @var array 
* $viewVars =
*   [
*     'user' => $user
*   ];
*
*/
	private $viewVars;

/**
* ビュークラス.
*
* @var TRW\View\ViewAdapter
*/
	private $view;

	public function __construct($request){
		$name = explode('\\', get_class($this));
		$this->name = substr(array_pop($name), 0, -10);

		$this->request = $request;
		
		$viewPath = App::path('view');
		$viewDir = $this->name;
		$layoutPath = App::path('layout');
		$elementPath = App::path('element');

		$this->view = new ViewAdapter($viewPath.'/'.$viewDir, $layoutPath, $elementPath, $this);
	}

/**
* コントローターの設定を行う.
*
* オーバーライド可能なメソッド
*
* このメソッド内ではコンポーネントクラスの読み込みなどを行うとよいでしょう
* またオーバーライドする時は必ずスーパークラスのメソッドを読んでください
* @return void
*/
	public function initialize(){

	}

/**
* ユーザーからのリクエストデータを取得する.
*
* ユーザーからのリクエストデータを利用したい時は
* このメソッドをでリクエストデータにアクセスできます
* 
* 返されるリクエストデーたとはPost,Query,Param,Sessionオブジェクトの集合オブジェクトです
* 
* それぞれのリクエストオブジェクトにアクセスするには
* getRequest()->getPost(),
* getRequest()->getQuery(),
* getRequest()->getParam(),
* getRequest()->getSession()
* でアクセスできます
*
* @return TRW\Request\RequestAggregate リクエストオブジェクトの集合
*/
	public function getRequest(){
		return $this->request;
	}

/**
* リクエストデータから継承先で実装されたメソッドを実行する.
*
* 使用禁止
*
* このメソッドは\TRW\Router\Dispatcherによって実行されます
*
* @param \TRW\Request\RequestAggregate $reqeust
* @return mixid 実行結果
*/
	public function invokeAction($request){
		$action = $request->getParam('action');
		$method = new ReflectionMethod($this, $action);
		return $method->invokeArgs($this, $request->getParam('arguments'));
	}

/**
* コンポーネントを読み込みを行う.
*
* このメソッドはinitializeの中で利用すると良いでしょう
* 読み込まれたコンポーネントは$this->Authのようにアクセスすることができます
*
*
* @param string $name コンポーネント名
* @param array $config 初期設定をしたい場合使う
* @return void
*/
	public function loadComponent($name, $config = null){
		$class = App::className($name . 'Component', 'Controller\\Component');
		if($class === false){
			throw new MissingComponentException('component not found ' . $name);
		}
		$this->{$name} = new $class($this, $config);
	}

/**
* Viewへ送るための変数を格納する
*
* @param array $vars
* $vars =
*   [
*     'user' => $user
*   ];
*/
	public function set($vars){
		$this->viewVars = $vars;
	}

/**
* Viewへ送るための変数のリストを取得する
*
* このメソッドはデバックのために使うと良いでしょう
*
* @return array
*/
	public function getViewVars(){
		return $this->viewVars;
	}

/**
* Viewをレンダリングする
* 
* 使用禁止
*
* このメソッドは\TRW\Router\Dispatcherによって実行される
* 
* @param string $viewFileName レンダリングするViewファイル名
* @return Viewの出力バッファ
*/
	public function render($viewFileName){

		$this->view->setViewVars($this->viewVars);
		return $this->view->render($viewFileName);
	}

/**
* リダイレクトを行う.
*
* @param array $param リダイレクト先の情報
* 次の様に定義する
* $param = 
*   [
*     'controller' => 'Users', 
*     'action' => 'view',
*     'arguments' => [
*       1,'foo'
*     ]
*   ];
*
*/
	public function redirect($param){
		$url = Router::normalize($param);
		header('Location:' . $url);	
		exit;		
	}

/**
* コンポーネントにアクセスする.
*
* 非推奨メソッド
*
* 安全にコンポーネントにアクセスするためのメソッドになるはず。。。
*
*/
	public function getComponent($name){
		if(isset($this->{$name})){
			return $this->{$name};
		}
		return false;
	}

/**
* ビューファイルの有無を検査する.
*
* @param string $fileName 検査したいビューファイル名, 拡張子は含めないでください
* @return boolean
*/
	public function fileExists($fileName){
		if($this->view->getViewFile($fileName) !== false)	{
			return true;
		}
		return false;
	}
	
/**
* コントローラーのinvokeAction実行前に行われるメソッド.
*
* 使用禁止
*
* このメソッドは\TRW\Router\Dispatcherによって実行される
*/
	public function startupProcess(){
		$this->initialize();
		$this->beforeFilter();		
	}

/**
* コントローラーのinvokeAction実行後に行われるメソッド.
*
* 使用禁止
*
* このメソッドは\TRW\Router\Dispatcherによって実行される
*/
	public function shutdownProcess(){
		$this->afterFilter();		
	}

/**
* invokeActionメソッド実行前に行われるメソッド.
*
* オーバーライド可能なメソッド
*
*/
	public function beforeFilter(){

	}

/**
* invokeActionメソッド実行後に行われるメソッド.
*
* オーバーライド可能なメソッド
*
*/
	public function afterFilter(){}

}
