<?php

namespace MVC\Framework;

use Exception;

class Route
{
  public $routes = [
    'GET' => [],
    'POST' => []
  ];

  public static function load($file)
  {
    $routes = new static;
    require $file;
    return $routes;
  }

  public function callRoute($url, $requestType)
  {
    if (array_key_exists($url, $this->routes[$requestType])) {
      $route = $this->routes[$requestType][$url];
      $controller = $route[0];
      $action = $route[1];
      return $this->callAction($controller, $action);
      // return $this->routes[$requestType][$url];
    }
    // return $this->routes['404'];
    throw new Exception('No route defined for this URL.');
  }

  public function callAction($controller, $action)
  {
    // $controller = "App\\App\\Http\\Controllers\\{$controller}";
    $controller = new $controller;
    if (!method_exists($controller, $action)) {
      throw new Exception(
        "{$controller} does not respond to the {$action} action."
      );
    }
    return $controller->$action();
  }


  public function get($uri, $controller)
  {
    $this->routes['GET'][$uri] = $controller;
  }

  public static function post()
  {
  }
}
