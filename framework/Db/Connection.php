<?php

namespace MVC\Framework\Db;

use PDO;
use PDOException;

class Connection
{
  public static function make($config)
  {
    try {
      return new PDO(
        'mysql:host=' . $config['connection'] . ':' . $config['port'] . ';' . 'dbname=' . $config['dbname'],
        $config['username'],
        $config['password'],
        $config['options']
      );
    } catch (PDOException $e) {
      die($e->getMessage());
    }
  }
}
