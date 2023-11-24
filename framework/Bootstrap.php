<?php

use LaraCore\Framework\Configuration;
use LaraCore\Framework\Request;
use LaraCore\Framework\Routers\Router;

session_start();
$config = Configuration::get('app');
date_default_timezone_set($config['timezone']);

include ROOT . DS . 'framework' . DS . "Helpers.php";
$web = ROOT . DS . 'routes' . DS . 'web.php';

Router::load($web)->callRouter(
  Request::uri(),
  Request::method()
);

session_destroy();