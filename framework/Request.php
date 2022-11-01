<?php

namespace MVC\Framework;

use MVC\Framework\Helpers\Input;
use MVC\Framework\Interfaces\RequestInterface;

class Request implements RequestInterface
{
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

  public static function method()
  {
    return $_SERVER['REQUEST_METHOD'];
  }

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

  public function isPost()
  {
    return $this->method() === 'POST' ? true : false;
  }

  public function isGet()
  {
    return $this->method() === 'GET' ? true : false;
  }

  /**
   * @method for redirect to another page
   * @param $url
   * @param $statusCode
   */
  public static function redirect($url = '/', $statusCode = null)
  {
    header('Location: ' . $url, true, $statusCode);
  }
}
