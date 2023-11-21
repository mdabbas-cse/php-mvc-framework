<?php

/**
 * Class Session
 * @package LaraCore\Framework
 */

namespace LaraCore\Framework;

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
