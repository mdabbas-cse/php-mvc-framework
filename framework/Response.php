<?php

namespace MVC\Framework;

class Response
{
  public static function setStatusCode($code)
  {
    http_response_code($code);
  }
}
