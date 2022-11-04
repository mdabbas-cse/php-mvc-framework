<?php

/**
 * Class Session
 * @package Lora\Core\Framework
 */

namespace Lora\Core\Framework;

class Session
{
  public static function set($key, $value)
  {
    $_SESSION[$key] = $value;
  }

  public static function get($key)
  {
    if (isset($_SESSION[$key])) {
      return $_SESSION[$key];
    }
    return false;
  }
}
