<?php

namespace LaraCore\Framework\Routers;

use Closure;
use LaraCore\App\Http\Kernel;
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
  public static function middlewareGroup($middleware, callable $callback)
  {
    // dd([$middleware, $callback]);
    $request = new Request();
    $middlewareAliases = Kernel::$middlewareAliases;
    $middleware = $middlewareAliases[$middleware];
    call_user_func($callback);
    self::runMiddleware($request, $middleware);
  }


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

  /**
   * Method to execute middleware
   * 
   * @param array $route
   * @return void
   */
  private static function executeMiddleware($route)
  {
    if (!isset($route['middleware']) || empty($route['middleware'])) {
      return;
    }
    $request = new Request();
    $middlewareAliases = Kernel::$middlewareAliases;
    foreach ($route['middleware'] as $middleware) {
      $middleware = $middlewareAliases[$middleware];
      self::runMiddleware($request, $middleware);
    }
  }

  private static function runMiddleware($request, $middleware)
  {
    if (isset($middleware)) {
      if (!class_exists($middleware)) {
        throw new \Exception("Middleware $middleware not found");
      }
    }
    $namespacePrefix = 'LaraCore\App';
    $middleware = $namespacePrefix . str_replace($namespacePrefix, '', $middleware);
    $middleware = new $middleware();
    $middleware->handle($request, function ($request) {
      return $request;
    });
  }

  /**
   * Method to get route name
   * 
   * @param string $name
   * @param array $params
   * @return string
   */
  public static function route($name, $params = [])
  {
    $route = array_filter(self::$routes, function ($route) use ($name) {
      return $route['name'] === $name;
    });
    if (count($route) > 0) {
      $route = array_values($route)[0];
      $uri = $route['uri'];
      foreach ($params as $key => $value) {
        $uri = str_replace('{' . $key . '}', $value, $uri);
      }
      return $uri;
    }
    return null;
  }
}