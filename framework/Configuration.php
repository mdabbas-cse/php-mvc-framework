<?php

namespace Lora\Core\Framework;

use Exception;

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
    $configFilePath = ROOT . DS . 'config' . DS . 'Config.php';
    if (!file_exists($configFilePath)) {
      throw new Exception("Config file not found at {$configFilePath}");
    }
    $config = require($configFilePath);
    if ($key && array_key_exists($key, $config)) {
      return $config[$key];
    }
    die("Config key \"{$key}\" not found");
  }
}