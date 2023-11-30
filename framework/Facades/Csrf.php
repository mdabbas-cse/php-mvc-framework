<?php

namespace LaraCore\Framework\Facades;

use LaraCore\Framework\Facades\Str;
use LaraCore\Framework\Request;

class Csrf
{
  /**
   * Csrf will change timeout is when finish
   */
  static $timeOut = (10 * 60);

  /**
   * Show csrf input
   */
  public static function csrf()
  {
    echo "<input type='hidden' name='_token' value='" . self::get() . "' />";
  }

  /**
   * Get Csrf Token
   * @return string
   */
  public static function get(): string
  {
    if ((!@$_SESSION['csrf_token'] || time() > @$_SESSION['csrf_token_timeout']))
      self::set();
    return $_SESSION['csrf_token'];
  }

  /**
   * Set Csrf Token randomly
   * @return void
   */
  public static function set()
  {
    $_SESSION['csrf_token_timeout'] = time() + self::$timeOut;
    $_SESSION['csrf_token'] = Str::rand(30);
  }

  /**
   * Unset Csrf Token
   */
  public static function unset()
  {
    unset($_SESSION['csrf_token']);
  }

  /**
   * Get remain time for timeout
   * @return int
   */
  public static function remainTimeOut(): int
  {
    return @$_SESSION['csrf_token_timeout'] - time();
  }

  /**
   * Check is a valid Csrf Token
   * $alwaysTrue parameter: if you wanna do not check it you can use $alwaysTrue = true
   * @param bool $alwaysTrue
   * @return bool
   */
  public static function check($alwaysTrue = false, Request $request): bool
  {
    if (($request->isGet() && $request->input('_token') !== self::get()) && $alwaysTrue !== true)
      return false;
    return true;
  }
}