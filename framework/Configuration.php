<?php

namespace LaraCore\Framework;

use Exception;

/**
 * Class Configuration
 * @package LaraCore\Framework
 */

class Configuration
{
  /**
   * @param $key
   * @return array
   */
  public static function get($key = null)
  {
    $all = self::all();
    if (array_key_exists($key, $all)) {
      return $all[$key];
    }
    throw new Exception("Config key \"{$key}\" not found");
  }

  /**
   * get all configs
   */
  public static function all()
  {
    $path = ROOT . DS . 'config' . DS . 'Config.php';
    if (!file_exists($path)) {
      throw new Exception("Config file not found");
    }
    return include(ROOT . DS . 'config' . DS . 'Config.php');
  }


}