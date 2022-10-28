<?php

use MVC\Framework\Configuration;
use MVC\Framework\Request;
use MVC\Framework\Router;

session_start();
$config = Configuration::get();
date_default_timezone_set($config['app']['timezone']);

include ROOT . DS . 'Framework' . DS . "Helpers.php";
$web = ROOT . DS . 'routes' . DS . 'web.php';

Router::load($web)->callRouter(
  Request::uri(),
  Request::method()
);

session_destroy();

// $db = new QueryBuilder(Connection::make($app['database']));

// $data = $db->selectAll('todos', Todo::class);

// $data = array_map(function ($item) {
//   $t =  new Todo();
//   $t->description = $item["name"];
// }, $data);
// dd($data);
// dd($url);
