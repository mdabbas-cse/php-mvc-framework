<?php

/**
 * Class Errors form show input value in input field
 */

namespace LaraCore\Framework\Helpers;

class Errors
{
  /**
   * @param $data
   * @return string
   */
  public static function get($key)
  {
    if (array_key_exists('inputErrors', $_SESSION) && !empty($_SESSION)) {
      if (array_key_exists($key, $_SESSION['inputErrors'])) {
        $message = $_SESSION['inputErrors'][$key];
        $_SESSION['inputErrors'][$key] = null;
        return $message;
      }
    }
    return '';
  }

  public static function set($errors)
  {
    if (!$errors)
      return;
    foreach ($errors as $key => $value) {
      $_SESSION['inputErrors'][$key] = $value;
    }
    dd($_SESSION['inputErrors']);
  }
}
