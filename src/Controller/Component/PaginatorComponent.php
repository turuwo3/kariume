<?php
namespace TRW\Controller\Component;

use TRW\Controller\Component;

class PaginatorComponent extends Component {

	private $page = 1;

	private $limit = 10;

	private $maxLimit = 20;

	private $order = 'id ASC';


	private $conditions = [
		'limit'=>10,
		'order'=>'id ASC'
	];

	private $model;

	private $controller;

	private $request;

	private $pageCount;

	public function __construct($controller){
		$this->controller = $controller;
		$this->request = $controller->getRequest();
	}


	public function initialize($model, $conditions){

		if(isset($conditions['limit']) && $conditions['limit'] >= $this->maxLimit){
			$conditions['limit'] = $this->maxLimit;
		}
		$this->conditions = array_merge($this->conditions, $conditions);
		
		$this->model = $model;

		
	}

	public function getCurrent(){
		return $this->page;
	}

	public function isNext(){
		$count = $this->count;
		$limit = $this->conditions['limit'];

		if($count > $this->page * $limit){
			return true;
		}
		return false;
	}

	public function isPrev(){
		$current = $this->page;
		if($current <= 1){
			return false;
		}

		return true;
	}

	public function paginate($options = []){
		$query = $this->request->getQuery('');
		if(isset($query['page'])){
			if(!is_numeric($query['page'])){
				throw new Exception('page not found');
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
			$this->pageCount = (int)ceil($model::rowCount($options) / $this->conditions['limit']);
			$this->count = $model::rowCount($options);
		}

		$requestPage = $this->page;
		$this->page = max(min($this->page, $this->pageCount), 1);

		if($requestPage > $this->page){
			throw new Exception('page not found');
		}

		$resultSet = $model::find($mergeConditions);

		return $resultSet;
	}


}





















