<?php

namespace LaraCore\Framework\Routers;

use LaraCore\Framework\Request;
use LaraCore\Framework\Response;

class Router
{

  /**
   * @var array
   */
  protected static $routes = [];
  /**
   * @var array
   */
  protected $middleware = [];

  /**
   * @var array
   */
  protected $middlewareGroups = [];

  /**
   * @var array
   */
  protected $middlewareAliases = [];

  /**
   * @var array
   */
  protected $middlewareGroupsAliases = [];

  public static function __callStatic($method, $args)
  {
    if (!in_array($method, ['get', 'post', 'delete', 'put'])) {
      throw new \Exception("Method $method not supported");
    }
    // dd($args);
    self::$routes[] = [
      'method' => strtoupper($method),
      'uri' => $args[0],
      'action' => $args[1],
      'middleware' => [],
      'name' => null,
    ];

    return new self();
  }

  /**
   * Method to set middleware
   * 
   * @param string $middleware
   */
  public function middleware($middleware)
  {
    self::$routes[count(self::$routes) - 1]['middleware'][] = $middleware;
    return $this;
  }

  /**
   * Method to set middleware group
   * 
   * @param string $middlewareGroup
   */
  public function middlewareGroup($middlewareGroup)
  {
  }

  /**
   * Method to set middleware alias
   * 
   * @param string $middlewareAlias
   */
  public function middlewareAlias($middlewareAlias)
  {
  }

  /**
   * Method to set middleware group alias
   * 
   * @param string $middlewareGroupAlias
   */
  public function middlewareGroupAlias($middlewareGroupAlias)
  {
  }

  /**
   * Method to set route name
   * 
   * @param string $name
   */
  public function name($name)
  {
    self::$routes[count(self::$routes) - 1]['name'] = $name;
    return $this;
  }

  /**
   * Method for dispatching route
   * 
   * @param Request $request
   * @return void
   */
  public static function dispatch(Request $request)
  {
    $uri = $request->uri();
    $method = $request->method();
    $route = self::findRoute($uri, $method);

    if (!$route) {
      return view('404');
    }

    self::executeMiddleware($route);

    return self::executeRoute($route);

  }

  /**
   * Method to find route
   * 
   * @param string $uri
   * @param string $method
   * @return array | false
   */
  private static function findRoute($uri, $method)
  {
    foreach (self::$routes as $route) {
      if ($route['uri'] === $uri && $route['method'] === $method) {
        return $route;
      }
    }

    return false;
  }

  /**
   * Method to execute route
   * 
   * @param array $route
   * @return mixed
   */
  private static function executeRoute($route)
  {
    $callback = $route['action'];
    $request = new Request();
    $response = new Response();

    if (is_array($callback)) {
      $namespacePrefix = 'LaraCore\App';

      $controller = $namespacePrefix . str_replace($namespacePrefix, '', $callback[0]);

      $callback[0] = new $controller();

      if (!class_exists($controller, $callback[1])) {
        throw new \Exception("Controller $callback[0] not found");
      }

      return call_user_func($callback, $request, $response);
    }

    if (is_callable($callback)) {
      return call_user_func($callback, $request, $response);
    }

    if (is_string($callback)) {
      return view($callback);
    }
  }

}