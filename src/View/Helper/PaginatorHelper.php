<?php
namespace TRW\View\Helper;

use TRW\View\Helper;

/**
* ページネーションするためのクラス.
*
*
*/
class PaginatorHelper  extends Helper{

/**
* ViewAdapterオブジェクト.
*
* @var \TRW\View\ViewAdapter
*/
	private $veiw;

/**
* コントローラーオブジェクト.
*
* @var \TRW\Controller\Contoller
*/
	private $controller;

/**
* リクエストデータ.
*
* @var \TRW\Request\RequestAggregate
*/
	private $request;

/**
* ページネーションのコンポーネント
*
* @var \TRW\Controller\Component_paginatorComponent
*/
	private $component;

/**
* 現在のページ番号.
*
* @var int
*/
	private $current;

	public function __construct($view){
		$this->view = $view;
		$this->controller = $view->getController();
		$this->request = $this->controller->getRequest();
		$this->component = $this->controller->getComponent('Paginator');
	}

/**
* 現在のページ番号を返す.
*
* @return int
*/
	public function current(){
		$current = $this->component->getCurrent();
		
		if($current === 0){
			return 1;
		}

		return $current;
	}

/**
* 次のページのリンクを生成する.
*
* @praram string $word "次の５件"などのワード
* @return string
*/
	public function next($word){
		if(!$this->component->isNext()){
			return null;
		}

		$next = $this->current() + 1;
		$query = "?page={$next}";

		return $this->link($query, $word);
	}

/**
* 前ののページのリンクを生成する.
*
* @praram string $word "前の５件"などのワード
* @return string
*/
	public function prev($word){
		if(!$this->component->isPrev()){
			return null;
		}
		$prev = $this->current() - 1;
		$query = "?page={$prev}";

		return $this->link($query, $word);	
	}

/**
* リンクを生成する.
*
* @param string $query getパラメーター
* @param string リンクに含めたい言葉
* @return string htmlリンク
*/
	private function link($query, $word){
		$request = $this->request;
		$controller = $request->getParam('controller');
		$action = $request->getParam('action');
		$args = implode('/', $request->getParam('arguments'));
		$link = "<a href='/{$controller}/{$action}/{$args}/{$query}'>{$word}</a>";

		return $link;
	}

}
