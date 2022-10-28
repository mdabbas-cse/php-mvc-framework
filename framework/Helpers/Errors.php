<?php

/**
 * Class Errors form show input value in input field
 */

namespace MVC\Framework\Helpers;

class Errors
{
  /**
   * @param $data
   * @return string
   */
  public static function get($key)
  {
    if (array_key_exists('inputErrors', $_SESSION) && !empty($_SESSION)) {
      $errors = $_SESSION['inputErrors'];
      $_SESSION['inputErrors'] = null;
      return $errors[$key];
    }
    return '';
  }

  public static function set($errors)
  {
    $_SESSION['inputErrors'] = $errors;
  }
}
