<?php

/**
 * Class Hash
 * @package Lora\Core\Framework
 * @version 1.0.0
 */

namespace Lora\Core\Framework\Helpers;

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
   * @function verify
   * @param $password 
   * @param $hash
   * @return bool
   * @description verify if password is correct
   */
  public static function verify($password, $hash)
  {
    return password_verify($password, $hash);
  }
}
