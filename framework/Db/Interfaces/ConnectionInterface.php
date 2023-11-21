<?php

namespace LaraCore\Framework\Db\Interfaces;

interface ConnectionInterface
{
  /**
   * @param array $config
   * @return \PDO
   */
  public static function make($config): \PDO;
}
