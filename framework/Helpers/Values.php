<?php

/**
 * Class Values form show input value in input field
 */

namespace MVC\Framework\Helpers;

class Values
{
  /**
   * @param $data
   * @return string
   */
  public static function get($key)
  {
    if (array_key_exists('inputValue', $_SESSION) && !empty($_SESSION)) {
      $values = $_SESSION['inputValue'];
      $_SESSION['inputValue'] = "";
      return $values[$key];
    }
    return '';
  }

  public static function set($values)
  {
    $_SESSION['inputValue'] = $values;
  }
}
