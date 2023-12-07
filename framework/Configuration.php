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
    $config = self::register($key);
    if ($config) {
      return $config;
    }
    die("Config key \"{$key}\" not found");
  }

  /**
   * register all config files
   */
  public static function register($key)
  {
    $fileArray = scandir(base_path('config'));
    $fileArray = array_diff($fileArray, ['.', '..']);

    $configFileArray = [];

    foreach ($fileArray as $configFile) {
      $getFileName = explode('.', $configFile);
      // if ($getFileName[0] == 'Config') {
      //   continue;
      // }
      $configFileArray[$getFileName[0]] = include_file("config/{$configFile}");
    }
    return $configFileArray[$key];
  }

}