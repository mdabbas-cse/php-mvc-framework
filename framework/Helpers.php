<?php

use Lora\Core\Framework\Configuration;
use Lora\Core\Framework\Helpers\Errors;
use Lora\Core\Framework\Helpers\Values;
use Lora\Core\Framework\View;

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
   * @param string $viewName
   * @param array $data 
   * @param option string $layout
   * @return View template
   */
  function view($viewName, $data = [], $layout = null)
  {
    $view = new View();
    if ($layout) {
      $view->setLayout($layout);
    }
    return $view->render($viewName, $data);
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
    $url = '/resources/assets/' . $path;
    return app_url($url);
  }
}


if (!function_exists('css')) {
  /**
   * @function css
   * @param $path
   * @return string
   */
  function css($path)
  {
    $url = '/resources/assets/css/' . trim($path, '/');
    return app_url($url);
  }
}

if (!function_exists('js')) {
  /**
   * @function js
   * @param $path
   * @return string
   */
  function js($path)
  {
    $url = '/resources/assets/js/' . trim($path, '/');
    return app_url($url);
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
    return $path ? $url . '/' . $path : $url;
  }
}

/**
 * @function for get input value
 * @param $key
 * @return string
 */
if (!function_exists('old')) {
  function old($key)
  {
    return Values::get($key);
  }
}

/**
 * @function for get error message
 * @param $key
 * @return string
 */
if (!function_exists('errors')) {
  function errors($key)
  {
    return Errors::get($key);
  }
}


include_once 'Components/InputsComponent.php';