<?php
namespace TRW\Controller\Component;

use TRW\Exception\NotFoundException;
use TRW\Controller\Component;

/**
* ページネーションを行うためのクラス
*
*
*
*/
class PaginatorComponent extends Component {

/**
* デフォルトのページ番号.
*
* @var int
*/
	private $page = 1;

/**
* レコードの取得件数.
*
* @var int
*/
	private $limit = 10;

/**
* レコードの最大取得件数.
*
* $limitがこの$maxLimitを超えた場合$maxLimitが上限値となる.
* 
* @var int $maxLimit
*/
	private $maxLimit = 20;

/**
* デフォルトのレコード取得条件.
*
* @var array
*/
	private $conditions = [
		'limit'=>10,
		'order'=>'id ASC'
	];

/**
* ページネーションするレコードクラス.
*
* @var \TRW\ActiveRecord\BaseRecord
*/
	private $model;

/**
* コントローラークラス.
*
* @var \TRW\Controller\Controller
*/
	private $controller;

/**
* リクエストデータ
*
* @var \TRW\Router\RequestAggregate
*/
	private $request;

/**
* ページ数.
*
* @var int
*/
	private $pageCount;

	public function __construct($controller){
		$this->controller = $controller;
		$this->request = $controller->getRequest();
	}

/**
* ページングするモデルとその条件を設定する.
*
* @var string $model レコードクラス App\Model\User
* @var array $conditions
* $conditions = 
*   [
*     'limit' => 12 ,
*     'order' => 'id DESC'
*   ];
*/
	public function initialize($model, $conditions){

		if(isset($conditions['limit']) && $conditions['limit'] >= $this->maxLimit){
			$conditions['limit'] = $this->maxLimit;
		}
		$this->conditions = array_merge($this->conditions, $conditions);
		
		$this->model = $model;

		
	}

/**
* 現在のページ番号を返す.
*
* @return int
*/
	public function getCurrent(){
		return $this->page;
	}

/**
* 次のページがあるか検査する.
*
* @return boolean 次のページがあればture　なければfase
*/
	public function isNext(){
		$count = $this->count;
		$limit = $this->conditions['limit'];

		if($count > $this->page * $limit){
			return true;
		}
		return false;
	}

/**
* 前ののページがあるか検査する.
*
* @return boolean 前のページがあればture　なければfase
*/
	public function isPrev(){
		$current = $this->page;
		if($current <= 1){
			return false;
		}

		return true;
	}

/**
* ページネーションを行う.
*
* @param array $options 検索条件
* $options = 
*  [
*    'where' => [
*      'field' => 'user_id',
*      'comparision' => '=',
*      'value' => 2
*    ]
*  ]
* @return \TRW\ActiveRecord\BaseRecord ページネーションされたレコードオブジェクト
*/
	public function paginate($options = []){

		$query = $this->request->getQuery();

		if(isset($query['page'])){
			if(!is_numeric($query['page'])){
				throw new NotFoundException('page not found');
			}

			$this->page = $query['page'];
		}

		$page = $this->page;
		$page--;
		$limit = $this->conditions['limit'];
		$offset = $page * $limit;
		$order = $this->conditions['order'];

		$conditions = [
			'limit'=>$limit,
			'offset'=>$offset,
			'order'=>$order
		];
		$mergeConditions = array_merge($this->conditions, $conditions);

		$model = $this->model;

		$this->pageCount = (int)ceil($model::rowCount() / $this->conditions['limit']);
		$this->count = $model::rowCount();

		if(isset($options)){
			$mergeConditions = array_merge($mergeConditions, $options);
			$this->pageCount = 
				(int)ceil($model::rowCount($mergeConditions) / $this->conditions['limit']);
			$this->count = $model::rowCount($mergeConditions);
		}

		$requestPage = $this->page;
		$this->page = max(min($this->page, $this->pageCount), 1);

		if($requestPage > $this->page){
			throw new NotFoundException('page not found');
		}

		$resultSet = $model::find($mergeConditions);

		return $resultSet;
	}


}





















