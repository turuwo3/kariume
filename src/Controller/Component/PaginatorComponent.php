<?php
namespace TRW\Controller\Component;

use TRW\Controller\Component;

class PaginatorComponent extends Component {

	private $page = 0;

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
		$this->pageCount = (int)ceil($model::rowCount() / $this->conditions['limit']);

		
	}

	public function getCurrent(){
		return $this->page;
	}

	public function isNext(){
		$pageCount = $this->pageCount;
		$current = $this->page;
		$current = max(min($current, $pageCount), 1);
		$limit = $this->conditions['limit'];

		if($pageCount > ($current * $limit)){
			return true;
		}
		return false;
	}

	public function isPrev(){
		$current = $this->page;
		if($current <= 0){
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
			if($query['page'] === 1){
				$this->page = 0;
			}

			if(!$this->isNext()){
				throw new Exception('page not found');
			}
		}
		$page = $this->page;
		
		$limit = $this->conditions['limit'];
		$offset = $page * $limit;
		$order = $this->conditions['order'];

		$conditions = [
			'limit'=>$limit,
			'offset'=>$offset,
			'order'=>$order
		];
		$mergeConditions = array_merge($this->conditions, $conditions);

		if(isset($options['where'])){
			$mergeConditions = array_merge($mergeConditions, ['where'=>$options['where']]);
		}

		$model = $this->model;

		$resultSet = $model::find($mergeConditions);

		return $resultSet;
	}


}





















