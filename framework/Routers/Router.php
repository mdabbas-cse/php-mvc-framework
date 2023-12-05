<?php

namespace LaraCore\Framework\Routers;

use LaraCore\App\Http\Kernel;
use LaraCore\Framework\Request;
use LaraCore\Framework\Response;
use LaraCore\Framework\RestApi\ApiHandler;

class Router
{

  /**
   * @var array
   */
  protected static $routes = [];
  /**
   * @var array
   */
  protected $middlewares = [];

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

  /**
   * @var string
   */
  protected static $useApiPrefix = '';

  public static function __callStatic($method, $args)
  {
    if (!in_array($method, ['get', 'post', 'delete', 'put'])) {
      throw new \Exception("Method $method not supported");
    }

    self::$routes[] = [
      'method' => strtoupper($method),
      'uri' => self::formatUri($args[0]),
      'action' => $args[1],
      'middleware' => [],
      'name' => null,
      'isApi' => !empty(self::$useApiPrefix) ? true : false,
    ];

    return new self();
  }
  /**
   * Method to format uri
   * 
   * @param string $uri
   * @return string
   */
  private static function formatUri($uri)
  {
    return self::$useApiPrefix ? '/api' . $uri : $uri;
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
   * @param string $middleware
   */
  public static function middlewareGroup($middleware, callable $callback)
  {
    $request = new Request();
    $middlewareAliases = Kernel::$middlewareAliases;
    $middleware = $middlewareAliases[$middleware];

    /** @var mixed */
    $previousMiddleware = self::$middlewares;
    self::$middlewares = [];

    call_user_func($callback);
    self::runMiddleware($request, $middleware);

    // Assign the current middleware to the routes in the group
    // $groupMiddleware = self::$middlewares;
    // self::$middlewares = $previousMiddleware;

    // foreach (self::$routes as &$route) {
    //   $route['middleware'] = array_merge($route['middleware'], $groupMiddleware);
    // }

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
  public static function dispatch(Request $request, Response $response)
  {
    $route = self::findRoute($request);
    if (!$route) {
      return view('404');
    }

    self::executeMiddleware($request, $route);

    return self::executeRoute($request, $response, $route);
  }

  /**
   * Method to find route
   * 
   * @param string $uri
   * @param string $method
   * @return array | false
   */
  private static function findRoute($request)
  {
    $uri = $request->uri();
    $method = $request->method();
    $routeParams = false;
    // dd(self::$routes);
    foreach (self::$routes as $route) {
      $routeName = [];
      $routeUri = $route['uri'];
      $routeMethod = $route['method'];
      if ($routeUri === $uri && $routeMethod === $method) {
        return $route;
      }
      if (preg_match_all('/\{(\w+)(:[^}]+)?}/', $routeUri, $matches)) {
        $routeName = $matches[1];
      }

      $routeUri = trim($routeUri, '/');

      // replace all route name with regex
      $routeRegex = "@^" . preg_replace_callback(
        '/\{\w+(:([^}]+))?}/',
        function ($m) {
          return isset($m[2]) ? "({$m[2]})" : '(\w+)';
        },
        $routeUri
      ) . "$@"; // end of regex

      if (preg_match_all($routeRegex, $uri, $matchesValue)) {
        $values = [];

        for ($i = 1; $i < count($matchesValue); $i++) {
          $values[] = $matchesValue[$i][0];
        }

        $routeParams = array_combine($routeName, $values);
        $request->setRouteParams($routeParams);
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
  private static function executeRoute($request, $response, $route)
  {
    $callback = $route['action'];

    // set api header of api route
    if ($route['isApi']) {
      $apiHandler = new ApiHandler($request, $response);
      $apiHandler->setApiHeaderWithAuth();
    }

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
  private static function executeMiddleware($request, $route)
  {
    if (!isset($route['middleware']) || empty($route['middleware'])) {
      return;
    }

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

  /**
   * Method to set api prefix
   * 
   * @param string $prefix
   * @return void
   */
  public static function setApiPrefix($prefix)
  {
    self::$useApiPrefix = true;
  }

  /**
   * Method to route group
   * 
   * @param string $prefix
   * @param callable $callback
   * @return static
   */
  public static function group($prefix, callable $callback)
  {
  }
}