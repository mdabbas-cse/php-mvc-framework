<?php

namespace Lora\Core\Framework\Db;

use Lora\Core\Framework\Configuration;
use PDO;
use Lora\Core\Framework\Db\Connection;
use Lora\Core\Framework\Db\Interfaces\QueryBuilderInterface;

class QueryBuilder implements QueryBuilderInterface
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
  public function prepare($sql): \PDOStatement
  {
    return $this->pdo->prepare($sql);
  }


  /**
   * select data from database
   */
  public function select($table, $columns = ['*'], $where = [], $intoClass = null)
  {
    $query = "SELECT " . implode(',', $columns) . " FROM {$table}";
    if (count($where) > 0) {
      $query .= " WHERE ";
      $query .= implode(' AND ', array_map(function ($key) {
        return "{$key} = :{$key}";
      }, array_keys($where)));
    }
    $statement = $this->prepare($query);
    if (count($where) > 0) {
      $x = 0;
      foreach ($where as $k => $param) {
        $statement->bindParam(":$k", $param);
        $x++;
      }
    }
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_CLASS, $intoClass);
  }

  /**
   * get data from database
   */
  public function get()
  {
    # code...
  }

  /**
   * @method for destruct data from database
   */
  public function __destruct()
  {
    $this->pdo = null;
  }
}
