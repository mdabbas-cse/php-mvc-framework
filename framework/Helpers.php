<?php

use MVC\Framework\Configuration;
use MVC\Framework\View;

if (!function_exists('dd')) {
  /**
   * @function dd
   * @param $data
   */
  function dd(...$data)
  {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
  }
}

if (!function_exists('view')) {
  /**
   * @function view
   * @param $viewName
   * @param array $data
   */
  function view($viewName, $data = [])
  {
    $view = new View();
    $view->render($viewName, $data);
  }
}

if (!function_exists('redirect')) {
  /**
   * @function redirect
   * @param $path
   */
  function redirect($path)
  {
    header("Location: {$path}");
  }
}

if (!function_exists('assets')) {
  /**
   * @function assets
   * @param $path
   * @return string
   */
  function assets($path)
  {
    global $config;
    dd($config);
    // return  $app['app']['web-root'] . DS . 'resources' . DS . 'assets' . DS . $path;
  }
}

if (!function_exists('app_url')) {
  /**
   * @function app_url
   * @param $path
   * @return string
   */
  function app_url($path = null)
  {
    $config = Configuration::get('app');
    $url = trim($config['root'], '/');
    $path = trim($path, '/');
    return  $path ? $url . '/' . $path : $url;
  }
}


include_once 'Components/InputsComponent.php';
