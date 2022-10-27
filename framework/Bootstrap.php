<?php

use MVC\Framework\Configuration;
use MVC\Framework\Request;
use MVC\Framework\Route;

$config = Configuration::get();
date_default_timezone_set($config['app']['timezone']);

include ROOT . DS . 'Framework' . DS . "Helpers.php";
$web = ROOT . DS . 'routes' . DS . 'web.php';

Route::load($web)->callRoute(
  Request::uri(),
  Request::method()
);

// $db = new QueryBuilder(Connection::make($app['database']));

// $data = $db->selectAll('todos', Todo::class);

// $data = array_map(function ($item) {
//   $t =  new Todo();
//   $t->description = $item["name"];
// }, $data);
// dd($data);
// dd($url);
