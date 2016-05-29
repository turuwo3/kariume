<?php

return [

	'App' => [
		'namespace' => 'App',
		'webroot' => 'webroot',
		'view' => '../app/View',
		'layout' => '../app/Layout'	,
		'element' => '../app/View/Element'
	],

	'Database'=>[
		'MySql' => [
			'dns'=>'mysql:dbname=test;host=localhost;charaset=utf8',
			'user'=>'****',
			'password'=>'****'
		]
	],

	'Security' => [
		'salt' => 'dfsdfsddgnt4523vvdfdsfasdfaeast4wr4rq34fafaewrfergergaergaergegaert43t34634534'
	],

	'Debug'=>[
		'level'=>0
	]


];
