<?php
require '../../vendor/autoload.php';


use TRW\Core\Configure;
use TRW\Router\Router;

Configure::load(dirname(__FILE__) . '/config.php');
Router::add('/', ['controller'=>'Pages', 'action'=>'index', 'arguments'=>[1]]);
