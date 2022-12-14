<?php

namespace Lora\Core\Framework;

/**
 * Class Configuration
 * @package Lora\Core\Framework
 */

class Configuration
{
  /**
   * @param $key
   * @return array
   */
  public static function get($key = null)
  {
    $config =  require(ROOT . DS . 'config' . DS . "Config.php");
    if ($key && array_key_exists($key, $config)) {
      return $config[$key];
    }
    die("Config key \"{$key}\" not found");
  }
}
