<?php

namespace Lora\Core\Framework;

use Lora\Core\Framework\Helpers\Input;
use Lora\Core\Framework\Interfaces\RequestInterface;

final class Request implements RequestInterface
{
  private $routeParams = [];

  /**
   * @method for url path
   * @return string
   */
  public static function uri()
  {
    $request = $_SERVER['REQUEST_URI'];
    if ($request === '/') {
      return '/';
    }
    return trim(
      parse_url($request, PHP_URL_PATH),
      '/'
    );
  }

  /**
   * @method for server request method
   * @return string
   */
  public static function method()
  {
    return $_SERVER['REQUEST_METHOD'];
  }

  /**
   * @method for get all request body
   * @return array
   */
  public function getBody()
  {
    $body = [];
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
  public function isPost()
  {
    return $this->method() === 'POST' ? true : false;
  }

  /**
   * @method for check request method is get
   * @return bool
   */
  public function isGet()
  {
    return $this->method() === 'GET' ? true : false;
  }

  /**
   * @method for check request method is put
   */
  public function isPut()
  {
    return $this->method() === 'PUT' ? true : false;
  }

  /**
   * @method for check request method is delete
   */
  public function isDelete()
  {
    return $this->method() === 'DELETE' ? true : false;
  }

  /**
   * @method for check request method is patch
   */
  public function isPatch()
  {
    return $this->method() === 'PATCH' ? true : false;
  }

  /**
   * @method for check request method is head
   */
  public function isHead()
  {
    return $this->method() === 'HEAD' ? true : false;
  }

  /**
   * @method for check request method is options
   */
  public function isOptions()
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
}
