<?php

namespace MVC\Framework;

class Request
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
}
