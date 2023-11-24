<?php

namespace LaraCore\Framework\Routers;

use Closure;
use LaraCore\Framework\Response;
use LaraCore\Framework\Request;
use LaraCore\Framework\Routers\RouterInterface;


class Router implements RouterInterface
{
  const GET = 'GET';
  const POST = 'POST';
  const PUT = 'PUT';
  const DELETE = 'DELETE';
  const PATCH = 'PATCH';
  const OPTIONS = 'OPTIONS';
  const HEAD = '';
  public $routes = [
    self::GET => [],
    self::POST => [],
    self::PUT => [],
    self::DELETE => [],
    self::PATCH => [],
    self::OPTIONS => [],
  ];

  /**
   * @inheritDoc
   * 
   * @return Router
   */
  public static function load($file): RouterInterface
  {
    $routes = new static;
    require_once($file);
    return $routes;
  }

  /**
   * Summary of getCallback which is used to get the callback function
   * 
   * @param mixed Request $request
   * @param mixed $url
   * @param mixed $method
   * @return @Router | boolean
   */
  private function getCallback(Request $request, $url, $method): Router|bool
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

  /**
   * @inheritDoc
   * 
   * @return mixed
   */
  public function callRouter($url, $requestType): mixed
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
   * @inheritDoc
   * 
   * @param mixed $uri
   * @param mixed $controller
   * @return RouterInterface
   */
  public function get($uri, $controller): RouterInterface
  {
    $this->routes[self::GET][$uri] = $controller;
    return new static;
  }

  /**
   * @inheritDoc
   * 
   * @return RouterInterface
   */
  public function post($uri, $controller): RouterInterface
  {
    $this->routes[self::POST][$uri] = $controller;
    return new static;
  }

  /**
   * @inheritDoc
   * 
   * @return RouterInterface
   */
  public function put($uri, $controller): RouterInterface
  {
    $this->routes[self::PUT][$uri] = $controller;
    return new static;
  }

  /**
   * @inheritDoc
   * 
   * @return RouterInterface
   */
  public function delete($uri, $controller): RouterInterface
  {
    $this->routes[self::DELETE][$uri] = $controller;
    return new static;
  }

  /**
   * @inheritDoc
   * 
   * @return RouterInterface
   */
  public function patch($uri, $controller): RouterInterface
  {
    $this->routes[self::PATCH][$uri] = $controller;
    return new static;
  }

  /**
   * @inheritDoc
   * 
   * @return RouterInterface
   */
  public function options($uri, $controller): RouterInterface
  {
    $this->routes[self::OPTIONS][$uri] = $controller;
    return new static;
  }

  /**
   * @inheritDoc
   * 
   * @return RouterInterface
   */
  public function any($uri, $controller): RouterInterface
  {
    $this->routes[self::GET][$uri] = $controller;
    $this->routes[self::POST][$uri] = $controller;
    $this->routes[self::PUT][$uri] = $controller;
    $this->routes[self::DELETE][$uri] = $controller;
    $this->routes[self::PATCH][$uri] = $controller;
    $this->routes[self::OPTIONS][$uri] = $controller;
    return new static;
  }

  /**
   * @inheritDoc
   * 
   * @return RouterInterface
   */
  public function group($prefix, $callback): RouterInterface
  {
    $callback(new static);
    return new static;
  }

  /**
   * @inheritDoc
   * 
   * @return RouterInterface
   */
  public function middleware($middleware): RouterInterface
  {
    return new static;
  }

  /**
   * @inheritDoc
   * 
   * @return $this object instance
   */
  public function name($name): RouterInterface
  {
    return new static;
  }
}