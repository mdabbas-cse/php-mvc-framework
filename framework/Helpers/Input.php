<?php

/**
 * Class Input for sanitizes user input
 * @package MVC\Framework
 */

namespace MVC\Framework\Helpers;

class Input
{
  /**
   * @param $data
   * @return string
   */
  public static function sanitize($data)
  {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  /**
   * function for sanitizes filter input get
   * @param $data
   * @return string
   */
  public static function filter_input_get($data)
  {
    return filter_input(INPUT_GET, $data, FILTER_SANITIZE_SPECIAL_CHARS);
  }

  /**
   * function for sanitizes filter input post
   * @param $data
   * @return string
   */
  public static function filter_input_post($data)
  {
    return filter_input(INPUT_POST, $data, FILTER_SANITIZE_SPECIAL_CHARS);
  }
}
