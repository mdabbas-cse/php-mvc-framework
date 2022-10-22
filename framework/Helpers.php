<?php

use MVC\Framework\View;

/**
 * @function dd
 * @param $data
 */
if (!function_exists('dd')) {
  function dd($data)
  {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
  }
}

/**
 * @function view
 * @param $viewName
 * @param array $data
 */
if (!function_exists('view')) {
  function view($viewName, $data = [])
  {
    $view = new View();
    $view->rander($viewName, $data);
  }
}

/**
 * @function redirect
 * @param $path
 */
if (!function_exists('redirect')) {
  function redirect($path)
  {
    header("Location: {$path}");
  }
}

/**
 * @function asset
 * @param $path
 * @return string
 */
if (!function_exists('assets')) {
  function assets($path)
  {
    global $config;
    dd($config);
    // return  $app['app']['web-root'] . DS . 'resources' . DS . 'assets' . DS . $path;
  }
}
