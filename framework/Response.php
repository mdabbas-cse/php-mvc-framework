<?php

namespace LaraCore\Framework;

class Response
{
  public static function setStatusCode($code)
  {
    http_response_code($code);
  }

  /**
   * @method for redirect to another page
   * @param $url
   * @param $statusCode
   */
  public function redirect($url = '/', $statusCode = null)
  {
    header('Location: ' . $url, true, $statusCode);
  }

  /**
   * @method for json response
   * @param $data
   * @param $statusCode
   */
  public function json($data, $statusCode = 200)
  {
    header('Content-Type: application/json');
    self::setStatusCode($statusCode);
    echo json_encode($data);
  }
}