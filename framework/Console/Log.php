<?php

namespace LaraCore\Framework\Console;

class Log
{
  /**
   * Log message to console
   * @param string $status
   * @param string $message
   * @return void
   */
  public static function add($status, $message)
  {
    $msg = [
      'success' => "\033[32m" . $message . "\033[0m\n",
      'error' => "\033[31m" . $message . "\033[0m\n",
      'warning' => "\033[33m" . $message . "\033[0m\n",
      'info' => "\033[34m" . $message . "\033[0m\n",
    ];
    echo $msg[$status];
  }

  /**
   * Summary of success
   * 
   * @param mixed $message
   * @return void
   */
  public static function success($message)
  {
    self::add('success', $message);
  }

  /**
   * Summary of error
   * @param mixed $message
   * @return void
   */
  public static function error($message)
  {
    self::add('error', $message);
  }

  /**
   * Summary of warning
   * @param mixed $message
   * @return void
   */
  public static function warning($message)
  {
    self::add('warning', $message);
  }

  /**
   * Summary of info
   * @param mixed $message
   * @return void
   */
  public static function info($message)
  {
    self::add('info', $message);
  }
}