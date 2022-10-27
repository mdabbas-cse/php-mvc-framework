<?php

namespace MVC\Framework;

use MVC\Framework\Response;

class Router
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

  public function callRouter($url, $requestType)
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

  public function get($uri, $controller)
  {
    $this->routes['GET'][$uri] = $controller;
    return new static;
  }

  public function post($uri, $controller)
  {
    $this->routes['POST'][$uri] = $controller;
    return new static;
  }

  public function put($uri, $controller)
  {
    $this->routes['PUT'][$uri] = $controller;
    return new static;
  }

  public function delete($uri, $controller)
  {
    $this->routes['DELETE'][$uri] = $controller;
    return new static;
  }

  public function patch($uri, $controller)
  {
    $this->routes['PATCH'][$uri] = $controller;
    return new static;
  }

  public function options($uri, $controller)
  {
    $this->routes['OPTIONS'][$uri] = $controller;
    return new static;
  }

  public function any($uri, $controller)
  {
    $this->routes['GET'][$uri] = $controller;
    $this->routes['POST'][$uri] = $controller;
    $this->routes['PUT'][$uri] = $controller;
    $this->routes['DELETE'][$uri] = $controller;
    $this->routes['PATCH'][$uri] = $controller;
    $this->routes['OPTIONS'][$uri] = $controller;
    return new static;
  }

  public function group($prefix, $callback)
  {
    $callback(new static);
    return new static;
  }

  public function middleware($middleware)
  {
    return new static;
  }

  public function name($name)
  {
    return new static;
  }

  public function namespace($namespace)
  {
    return new static;
  }
}
