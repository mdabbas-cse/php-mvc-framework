<?php

namespace MVC\Framework;

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
    if ($this->method() === 'GET') {
      foreach ($_GET as $key => $value) {
        $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
      }
    }
    if ($this->method() === 'POST') {
      foreach ($_POST as $key => $value) {
        $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
      }
    }
    return $body;
  }

  public function isPost()
  {
    return $this->method() === 'POST' ? true : false;
  }

  public function isGet()
  {
    return $this->method() === 'GET' ? true : false;
  }
}
