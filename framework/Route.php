<?php

namespace MVC\Framework;

use Exception;
use MVC\Framework\Response;

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
    $request = new Request();
    if (array_key_exists($url, $this->routes[$requestType])) {

      $callback = $this->routes[$requestType][$url];

      // check if route is a callback function render closure
      if (is_object($callback)) {
        return call_user_func($callback);
      }

      // check if route is a string, then render the view
      if (is_string($callback)) {
        return view($callback);
      }

      // by default, route is an array, then render the controller
      if (is_array($callback)) {
        $callback[0] = new $callback[0]();
      }

      return call_user_func($callback, $request);
    }

    Response::setStatusCode(404);
    return view('404');
  }

  // public function callAction($controller, $action)
  // {
  //   $controller = new $controller;
  //   if (!method_exists($controller, $action)) {
  //     throw new Exception(
  //       "{$controller} does not respond to the {$action} action."
  //     );
  //   }
  //   call_user_func_array([$controller, $action], []);
  // }


  public function get($uri, $controller)
  {
    $this->routes['GET'][$uri] = $controller;
  }

  public function post($uri, $controller)
  {
    $this->routes['POST'][$uri] = $controller;
  }
}
