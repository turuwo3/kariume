<?php

$condition = [
	'where'=>[
		'field'=>'id',
		'comparisino'=>'=',
		'value'=>'1'
	],
	'limit'=>'220',
	'order'=>'id DESC'
];


class P{

	public $limit = 10;
	public $maxLimit = 20;
	public $order = 'id ASC';

	public $condition = [
		'limit'=>10,
		'order'=>'id ASC'
	];

	public function initialize($condition){
		if(isset($condition['limit']) && $condition['limit'] >= $this->maxLimit){
			$condition['limit'] = $this->maxLimit;
		}
		$this->condition = array_merge($this->condition, $condition);
	}


}


$p = new P;

$p->initialize($condition);

print_r($p->condition);
