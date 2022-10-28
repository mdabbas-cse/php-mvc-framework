<?php

/**
 * Class Hash
 * @package MVC\Framework
 * @version 1.0.0
 */

namespace MVC\Framework\Helpers;

class Hash
{

  /**
   * @param $password
   * @return bool|string
   */
  public static function make($password)
  {
    return password_hash($password, PASSWORD_DEFAULT);
  }

  /**
   * @param $password
   * @param $hash
   * @return bool
   */
  public static function check($password, $hash)
  {
    return password_verify($password, $hash);
  }
}
