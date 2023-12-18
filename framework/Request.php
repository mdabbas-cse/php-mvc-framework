<?php

namespace LaraCore\Framework;

use LaraCore\Framework\Helpers\Input;
use LaraCore\Framework\Interfaces\RequestInterface;

final class Request implements RequestInterface
{
  private $routeParams = [];

  private $queryParams = [];

  public function __construct()
  {


  }

  /**
   * @method for url path
   * @return string
   */
  public function uri(): string
  {
    $request = $_SERVER['REQUEST_URI'];
    if ($request === '/') {
      return '/';
    }

    $request = trim(
      parse_url($request, PHP_URL_PATH),
      '/'
    );
    return $request;
  }

  /**
   * @method for server request method
   * @return string
   */
  public function method(): string
  {
    return $_SERVER['REQUEST_METHOD'];
  }

  /**
   * @method for get all request body
   * 
   * @return object
   */
  public function all(): array
  {
    $body = [];
    // for route params
    if ($this->routeParams) {
      foreach ($this->getRouteParams() as $key => $value) {
        $body[$key] = Input::sanitize($value);
        $this->{$key} = Input::sanitize($value);
      }
    }
    if ($this->isGet()) {
      foreach ($_GET as $key => $value) {
        $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        $this->{$key} = Input::sanitize($value);
      }
    }
    if ($this->isPost()) {
      foreach ($_POST as $key => $value) {
        $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        $this->{$key} = Input::sanitize($value);
      }
    }
    return $body;
  }

  /**
   * get request method value
   */
  public function input($key)
  {
    if (property_exists($this, $key)) {
      return Input::sanitize($this->{$key});
    }
    throw new \Exception("{$key} not found");
  }

  /**
   * @method for check request method is post
   * @return bool
   */
  public function isPost(): bool
  {
    return $this->method() === 'POST' ? true : false;
  }

  /**
   * @method for check request method is get
   * @return bool
   */
  public function isGet(): bool
  {
    return $this->method() === 'GET' ? true : false;
  }

  /**
   * @method for check request method is put
   */
  public function isPut(): bool
  {
    return $this->method() === 'PUT' ? true : false;
  }

  /**
   * @method for check request method is delete
   * 
   * @return bool
   */
  public function isDelete(): bool
  {
    return $this->method() === 'DELETE' ? true : false;
  }

  /**
   * @method for check request method is patch
   * 
   * @return bool
   */
  public function isPatch(): bool
  {
    return $this->method() === 'PATCH' ? true : false;
  }

  /**
   * @method for check request method is head
   * 
   * @return bool
   */
  public function isHead(): bool
  {
    return $this->method() === 'HEAD' ? true : false;
  }

  /**
   * @method for check request method is options
   * 
   * @return bool
   */
  public function isOptions(): bool
  {
    return $this->method() === 'OPTIONS' ? true : false;
  }

  /**
   * @method for check request method is connect
   */
  public function isConnect()
  {
    return $this->method() === 'CONNECT' ? true : false;
  }

  /**
   * @method for check request method is trace
   */
  public function isTrace()
  {
    return $this->method() === 'TRACE' ? true : false;
  }

  /**
   * @method for set request params
   * @param $key
   * @return object
   */
  public function setRouteParams($params)
  {
    $this->routeParams = $params;
    return $this;
  }

  /**
   * @method for get request params
   * @return array
   */
  public function getRouteParams()
  {
    return $this->routeParams;
  }

  /**
   * @method for get request param
   * @param $key
   * @return string | number
   */
  public function getParam($key)
  {
    return $this->routeParams[$key];
  }

  /**
   * @method for check http authorization
   * 
   * @return bool
   */
  public function isHttpAuthorizedOrFail()
  {
    // api key get from header
    $apiKey = isset($_SERVER['HTTP_API_KEY']) ? $_SERVER['HTTP_API_KEY'] : false;
    error_log($apiKey);
    return $apiKey;
  }


  /**
   * @medium for set json header
   * 
   * @return void
   */
  public function setJsonHeader()
  {
    header('Content-Type: application/json');
  }

  /**
   * @method  for setUnauthorized 
   */
  public function setUnauthorizedHeader()
  {
    header('HTTP/1.0 401 Unauthorized');
  }

  /**
   * 
   */
  public function withAttribute($key, $value)
  {
    $this->{$key} = $value;
    return $this;
  }

}