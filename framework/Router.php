<?php

namespace MVC\Framework;

use MVC\Framework\Response;
use MVC\Framework\Request;

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

  /**
   * @method for getCallback
   * @param $uri
   * @param $controller
   */
  private function getCallback($url, $method)
  {
    $url = trim($url, '/');
    $routes  = $this->routes[$method] ?? [];

    $routeParams = false;

    foreach ($routes as $route => $callback) {
      echo '<pre>';
      echo 'route: ' . $route . '<br>';
      var_dump($callback);
      // echo '</pre>';

      $route = trim($route, '/');
      $routeName = [];

      if (!$route)  continue;
      // find all route name from route name
      if (preg_match_all('/\{(\w+)(:[^}]+)?}/', $route, $matches)) {
        $routeName = $matches[1];
      }
      // echo '<pre>';
      echo 'routeName: ';
      var_dump($routeName);


      // replace all route name with regex
      $routeRegex = "@^" . preg_replace_callback(
        '/\{\w+(:([^}]+))?}/',
        function ($m) {
          return isset($m[2]) ? "({$m[2]})" : '(\w+)';
        },
        $route
      ) . "$@"; // end of regex


      // var_dump($routeName);
      // var_dump($url);
      var_dump($routeRegex);
      echo '</pre>';
      echo '<hr>';
      // test and match current route with route regex
      if (preg_match_all($routeRegex, $url, $matchesValue)) {
        echo '<pre>';
        var_dump($matchesValue);
        echo '</pre>';
        // array_shift($matches);
        // $routeParams = array_combine($routeName, $matches);
        // break;
      }
    }
  }

  public function callRouter($url, $requestType)
  {
    $request = new Request();
    $response = new Response();
    $callback = $this->routes[$requestType][$url] ?? false;
    if (!$callback) {
      $callback = $this->getCallback($url, $requestType);
      // dd($callback);
    }
    if (array_key_exists($url, $this->routes[$requestType])) {
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

      return call_user_func($callback, $request, $response);
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
