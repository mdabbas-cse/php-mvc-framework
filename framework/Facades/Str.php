<?php

namespace LaraCore\Framework\Facades;

class Str
{
  /**
   * Limit length a text
   * @param string $text
   * @param int $length
   * @param string $continue
   * @return string
   */
  public static function limit(string $text, int $length = 50, string $continue = "..."): string
  {
    if (strlen($text) > $length)
      $text = substr($text, 0, $length) . $continue;
    return $text;
  }

  /**
   * Create Random String
   * @param int $length
   * @param bool $unique
   * @return string
   */
  public static function rand(int $length = 5, bool $unique = false): string
  {
    $q = "QWERTYUIOPASDFHJKLZXCVBNMqwertyuopasdfghjklizxcvbnm0987654321";
    $q_count = strlen($q) - 1;
    $r = "";
    for ($x = $length; $x > 0; $x--)
      $r .= $q[rand(0, $q_count)];
    return $r . ($unique ? uniqid('', true) : null);
  }

  /**
   * Text Convert To Slug Url
   * @param string $text
   * @param string $divider
   * @return string
   */
  public static function slug(string $text, string $divider = '-'): string
  {
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', $divider, $text)));
    ;
  }
}
