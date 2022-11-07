<?php

namespace Lora\Core\Framework\Interfaces;

interface RequestInterface
{
  public static function uri();
  public static function method();
  public function getBody();
  public function isPost();
  public function isGet();
}
