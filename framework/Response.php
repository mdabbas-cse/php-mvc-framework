<?php

namespace LaraCore\Framework;

use LaraCore\Framework\Routers\Router;

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
    $app_url = app_url();
    $url = $app_url . $url;
    header('Location: ' . $url, true, $statusCode);
    $this;
  }

  /**
   * @method for json response
   * @param $data
   * @param $statusCode
   */
  public function json($data, $statusCode = 200)
  {
    header('Content-Type: application/json; charset=utf-8');
    self::setStatusCode($statusCode);
    echo json_encode($data, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);
    exit;
  }

  /**
   * @method for route name
   * 
   * @param $name
   * @param $params
   * @return void
   */
  public function route($name, $params = [])
  {
    $url = Router::route($name, $params);
    if ($url) {
      return $this->redirect($url);
    }
  }

  /**
   * @json response
   * 
   * @param $data
   * @param $statusCode
   * @return void
   */
  public function jsonResponse($data, $statusCode = 200)
  {
    $this->json($data, $statusCode);
  }
}