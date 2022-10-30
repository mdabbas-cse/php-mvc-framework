<?php

namespace MVC\Framework\Db;

use MVC\Framework\Configuration;
use PDO;
use MVC\Framework\Db\Connection;

class QueryBuilder
{
  protected $pdo;

  public function __construct()
  {
    $config = Configuration::get('database');
    $connection =  Connection::make($config);
    $this->pdo = $connection;
  }

  public function selectAll($table, $intoClass = null)
  {
    $statement = $this->prepare("select * from {$table}");
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_CLASS, $intoClass);
  }

  /**
   * prepare sql statement
   */
  public function prepare($sql)
  {
    return $this->pdo->prepare($sql);
  }
}
