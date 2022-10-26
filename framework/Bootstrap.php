<?php

use MVC\Framework\Db\Connection;
use MVC\Framework\Db\QueryBuilder;
use MVC\Framework\App;
use MVC\Framework\Request;
use MVC\Framework\Route;

$config =  require(ROOT . DS . 'config' . DS . "Config.php");

define('DEBUG', $config['debug']);

App::bind('config', $config);
$app = App::get('config');
include ROOT . DS . 'Framework' . DS . "Helpers.php";
$web = ROOT . DS . 'routes' . DS . 'web.php';

Route::load($web)->callRoute(
  Request::uri(),
  Request::method()
);

$db = new QueryBuilder(Connection::make($app['database']));

// $data = $db->selectAll('todos', Todo::class);

// $data = array_map(function ($item) {
//   $t =  new Todo();
//   $t->description = $item["name"];
// }, $data);
// dd($data);
// dd($url);
