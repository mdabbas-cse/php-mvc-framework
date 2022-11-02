<?php

use MVC\Framework\Configuration;
use MVC\Framework\Request;
use MVC\Framework\Router;

session_start();
$config = Configuration::get('app');
date_default_timezone_set($config['timezone']);

include ROOT . DS . 'Framework' . DS . "Helpers.php";
$web = ROOT . DS . 'routes' . DS . 'web.php';

Router::load($web)->callRouter(
  Request::uri(),
  Request::method()
);

session_destroy();
