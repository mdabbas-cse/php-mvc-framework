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

      // check if route is a callback function render closure
      if (is_object($route)) {
        return call_user_func($route);
      }

      // check if route is a string, then render the view
      if (is_string($route)) {
        return view($route);
      }

      // by default, route is an array, then render the controller
      $controller = $route[0];
      $action = $route[1];
      return $this->callAction($controller, $action);
    }
    return view('404');
  }

  public function callAction($controller, $action)
  {
    $controller = new $controller;
    if (!method_exists($controller, $action)) {
      throw new Exception(
        "{$controller} does not respond to the {$action} action."
      );
    }
    call_user_func_array([$controller, $action], []);
  }


  public function get($uri, $controller)
  {
    $this->routes['GET'][$uri] = $controller;
  }

  public function post($uri, $controller)
  {
    $this->routes['POST'][$uri] = $controller;
  }
}
