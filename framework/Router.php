<?php

namespace LaraCore\Framework;

use LaraCore\Framework\Response;
use LaraCore\Framework\Request;

class Router
{
  public $routes = [
    'GET' => [],
    'POST' => []
  ];

  public static function load($file)
  {
    $routes = new static;
    require_once($file);
    return $routes;
  }

  /**
   * @method for getCallback
   * @param $uri
   * @param $controller
   */
  private function getCallback(Request $request, $url, $method)
  {
    $url = trim($url, '/');
    $routes = $this->routes[$method] ?? [];

    $routeParams = false;

    foreach ($routes as $route => $callback) {
      $route = trim($route, '/');
      $routeName = [];

      if (!$route)
        continue;
      // find all route name from route name
      if (preg_match_all('/\{(\w+)(:[^}]+)?}/', $route, $matches)) {
        $routeName = $matches[1];
      }

      // replace all route name with regex
      $routeRegex = "@^" . preg_replace_callback(
        '/\{\w+(:([^}]+))?}/',
        function ($m) {
          return isset($m[2]) ? "({$m[2]})" : '(\w+)';
        },
        $route
      ) . "$@"; // end of regex

      // test and match current route with route regex
      if (preg_match_all($routeRegex, $url, $matchesValue)) {
        $values = [];
        for ($i = 1; $i < count($matchesValue); $i++) {
          $values[] = $matchesValue[$i][0];
        }

        $routeParams = array_combine($routeName, $values);
        $request->setRouteParams($routeParams);
        return $callback;
      }
    }
    return false;
  }

  public function callRouter($url, $requestType)
  {
    $request = new Request();
    $response = new Response();
    $callback = $this->routes[$requestType][$url] ?? false;
    if (!$callback) {
      $callback = $this->getCallback($request, $url, $requestType);
      if ($callback === false) {
        Response::setStatusCode(404);
        return view('404');
      }
    }

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

  /**
   * @method for get request
   * @param $uri
   * @param $controller
   * @return $this object instance
   */
  public function get($uri, $controller)
  {
    $this->routes['GET'][$uri] = $controller;
    return new static;
  }

  /**
   * @method for post request
   * @param $uri
   * @param $controller
   * @return $this object instance
   */
  public function post($uri, $controller)
  {
    $this->routes['POST'][$uri] = $controller;
    return new static;
  }

  /**
   * @method for put request
   * @param $uri
   * @param $controller
   * @return $this object instance
   */
  public function put($uri, $controller)
  {
    $this->routes['PUT'][$uri] = $controller;
    return new static;
  }

  /**
   * @method for delete request
   * @param $uri
   * @param $controller
   * @return $this object instance
   */
  public function delete($uri, $controller)
  {
    $this->routes['DELETE'][$uri] = $controller;
    return new static;
  }

  /**
   * @method for patch request
   * @param $uri
   * @param $controller
   * @return $this object instance
   */
  public function patch($uri, $controller)
  {
    $this->routes['PATCH'][$uri] = $controller;
    return new static;
  }

  /**
   * @method for options request
   * @param $uri
   * @param $controller
   * @return $this object instance
   */
  public function options($uri, $controller)
  {
    $this->routes['OPTIONS'][$uri] = $controller;
    return new static;
  }

  /**
   * @method for any request
   * @param $uri
   * @param $controller
   * @return $this object instance
   */
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

  /**
   * @method for group request
   * @param $uri
   * @param $controller
   * @return $this object instance
   */
  public function group($prefix, $callback)
  {
    $callback(new static);
    return new static;
  }

  /**
   * @method for group request
   * @param $uri
   * @param $controller
   * @return $this object instance
   */
  public function middleware($middleware)
  {
    return new static;
  }

  /**
   * @method for group request
   * @param $uri
   * @param $controller
   * @return $this object instance
   */
  public function name($name)
  {
    return new static;
  }
}