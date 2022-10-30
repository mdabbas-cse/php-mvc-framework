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
   * @return string $hash
   * @description hash password
   */
  public static function make($password)
  {
    return password_hash($password, PASSWORD_DEFAULT);
  }

  /**
   * @function check
   * @param $password 
   * @param $hash
   * @return bool
   * @description check if password is correct
   */
  public static function check($password, $hash)
  {
    return password_verify($password, $hash);
  }
}
