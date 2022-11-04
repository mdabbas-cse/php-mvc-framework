<?php

namespace Lora\Core\Framework\Db;

use Lora\Core\Framework\Db\Exceptions\DatabaseConnectionException;
use Lora\Core\Framework\Db\Interfaces\ConnectionInterface;
use PDO;
use PDOException;

class Connection implements ConnectionInterface
{
  public static function make($config): PDO
  {
    try {
      return new PDO(
        'mysql:host=' . $config['connection'] . ':' . $config['port'] . ';' . 'dbname=' . $config['dbname'],
        $config['username'],
        $config['password'],
        $config['options']
      );
    } catch (PDOException $e) {
      throw new DatabaseConnectionException($e->getMessage(), (int)$e->getCode());
    }
  }
}
