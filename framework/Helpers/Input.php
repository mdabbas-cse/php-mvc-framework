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
}
