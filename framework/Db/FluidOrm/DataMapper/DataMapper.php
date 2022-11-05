<?php

namespace Lora\Core\Framework\Db\FluidOrm\DataMapper;

use Lora\Core\Framework\Db\FluidOrm\Exceptions\DataMapperException;
use Lora\Core\Framework\Db\FluidOrm\Interfaces\DataMapperInterface;
use PDOStatement;
use Throwable;

class DataMapper implements DataMapperInterface
{
  /**
   * @var \PDO
   */
  private $pdo;

  /**
   * @var \PDOStatement
   */
  private $statement;

  /**
   * @var array
   */
  private $params = [];

  /**
   * @var array
   */
  private $results = [];

  /**
   * @var int
   */
  private $numRows = 0;

  /**
   * @var string
   */
  private $sql;

  /**
   * @var string
   */
  private $table;

  /**
   * @var array
   */
  private $columns = [];

  /**
   * @var array
   */
  private $where = [];

  /**
   * @var string
   */
  private $intoClass;

  /**
   * @param \PDO $pdo
   */
  public function __construct(\PDO $pdo)
  {
    $this->pdo = $pdo;
  }
  /**
   * @method for checking if isEmpty
   * @param string $value
   * @param string $message
   * @return void
   */
  private function isEmpty(string $value, string $message = null): void
  {
    if (empty($value)) {
      throw new DataMapperException($message);
    }
  }

  /**
   * @method for checking if isString
   * @param string $value
   * @return void
   */
  private function isString(string $value): void
  {
    if (!is_string($value)) {
      throw new DataMapperException('The value must be a string');
    }
  }

  /**
   * @method for checking if isInt
   * @param int $value
   * @return void
   */
  private function isInt(int $value): void
  {
    if (!is_int($value)) {
      throw new DataMapperException('The value must be an integer');
    }
  }

  /**
   * @method for checking if isBool
   * @param bool $value
   * @param string $message
   * @return void
   */
  private function isBool(bool $value): void
  {
    if (!is_bool($value)) {
      throw new DataMapperException('The value must be a boolean');
    }
  }

  /**
   * @method for checking if isArray
   * @param array $value
   * @return void
   */
  private function isArray(array $value): void
  {
    if (!is_array($value)) {
      throw new DataMapperException('The value must be an array');
    }
  }

  /**
   * @InheritDoc
   */
  public function prepare(string $sql): self
  {
    $this->sql = $sql;
    $this->statement = $this->pdo->prepare($sql);
    return $this;
  }

  /**
   * @InheritDoc
   */
  public function bind($value)
  {
    try {
      switch ($value) {
        case is_int($value):
          $dataType = \PDO::PARAM_INT;
          break;
        case is_bool($value):
          $dataType = \PDO::PARAM_BOOL;
          break;
        case is_null($value):
          $dataType = \PDO::PARAM_NULL;
          break;
        default:
          $dataType = \PDO::PARAM_STR;
      }
      return $dataType;
    } catch (DataMapperException $message) {
      throw $message;
    }
  }

  /**
   * @InheritDoc
   * @Exception DataMapperException
   */
  protected function bindValues(array $fields): PDOStatement
  {
    try {
      $this->isArray($fields);
      foreach ($fields as $key => $value) {
        $this->statement->bindValue(':' . $key, $value, $this->bind($value));
      }
      return $this->statement;
    } catch (\Exception $e) {
      throw new DataMapperException($e->getMessage(), $e->getCode(), $e);
    }
  }

  /**
   * @InheritDoc
   * @Exception DataMapperException
   */
  protected function bindSearchValues(array $fields): \PDOStatement
  {
    try {
      $this->isArray($fields);
      foreach ($fields as $key => $value) {
        $this->statement->bindValue(':' . $key, '%' . $value . '%', $this->bind($value));
      }
      return $this->statement;
    } catch (\Exception $e) {
      throw new DataMapperException($e->getMessage(), $e->getCode(), $e);
    }
  }

  /**
   * @InheritDoc
   */
  public function bindParameters(array $fields, bool $isSearch = false): self
  {
    if (is_array($fields)) {
      $type = $isSearch === false ? $this->bindValues($fields) : $this->bindSearchValues($fields);
      if ($type) return $this;
    }
  }

  /**
   * @InheritDoc
   */
  public function numRows(): int
  {
    if ($this->statement)
      return $this->statement->rowCount();
  }

  /**
   * @InheritDoc
   */
  public function execute(): void
  {
    if ($this->statement)
      $this->statement->execute();
  }

  /**
   * @InheritDoc
   */
  public function result(): Object
  {
    if ($this->statement)
      return $this->statement->fetchObject();
  }

  /**
   * @InheritDoc
   */
  public function results(): array
  {
    if ($this->statement)
      return $this->statement->fetchAll(\PDO::FETCH_CLASS, $this->intoClass);
  }

  /**
   * @InheritDoc
   */
  public function getLastId(): int
  {
    try {
      $lastId =  $this->pdo->lastInsertId();
      if (!empty($lastId)) {
        return intval($lastId);
      }
    } catch (Throwable $throwable) {
      throw new DataMapperException($throwable->getMessage(), $throwable->getCode(), $throwable);
    }
  }

  // /**
  //  * @param string $table
  //  * @param array $columns
  //  * @param array $where
  //  * @param string|null $intoClass
  //  * @return array
  //  */
  // public function select(string $table, array $columns = ['*'], array $where = [], string $intoClass = null): array
  // {
  //   $this->table = $table;
  //   $this->columns = $columns;
  //   $this->where = $where;
  //   $this->intoClass = $intoClass;

  //   $this->sql = "SELECT " . implode(', ', $this->columns) . " FROM {$this->table}";

  //   if (!empty($this->where)) {
  //     $this->sql .= " WHERE ";
  //     $i = 0;
  //     foreach ($this->where as $key => $value) {
  //       if ($i > 0) {
  //         $this->sql .= " AND ";
  //       }
  //       $this->sql .= "{$key} = :{$key}";
  //       $this->bind($value);
  //       $this->bindParams($key);
  //       $i++;
  //     }
  //   }

  //   $this->prepare($this->sql);
  //   return $this->results();
  // }

  // /**
  //  * @param string $table
  //  * @param array $columns
  //  * @param array $where
  //  * @param string|null $intoClass
  //  * @return array
  //  */
  // public function all(string $table, string $intoClass = null): array
  // {
  //   $this->table = $table;
  //   $this->intoClass = $intoClass;

  //   $this->sql = "SELECT * FROM {$this->table}";

  //   $this->prepare($this->sql);
  //   return $this->results();
  // }

  // /**
  //  * @param string $table
  //  * @param array $columns
  //  * @param array $where
  //  * @param string|null $intoClass
  //  * @return array
  //  */
  // // public function insert(string $table, array $columns = [], array $where = [], string $intoClass = null): array
  // // {
  // //   $this->table = $table;
  // //   $this->columns = $columns;
  // //   $this->where = $where;
  // //   $this->intoClass = $intoClass;

  // //   $this->sql = "INSERT INTO {$this->table} (" . implode(', ', $this->columns) . ") VALUES (:" . implode(', :', $this->columns) . ")";

  // //   $this->prepare($this->sql);

  // //   $i = 0;
  // //   foreach ($this->columns as $column) {
  // //     $this->bindParams($column);
  // //     $this->statement->bindValue(":{$column}", $this->params[$i]);
  // //     $i++;
  // //   }

  // //   $this->execute();
  // //   return $this->results();
  // // }

  // /**
  //  * @param string $table
  //  * @param array $columns
  //  * @param array $where
  //  * @param string|null $intoClass
  //  * @return array
  //  */
  // // public function update(string $table, array $columns = [], array $where = [], string $intoClass = null): array
  // // {
  // //   $this->table = $table;
  // //   $this->columns = $columns;
  // //   $this->where = $where;
  // //   $this->intoClass = $intoClass;

  // //   $this->sql = "UPDATE {$this->table} SET ";

  // //   $i = 0;
  // //   foreach ($this->columns as $column) {
  // //     if ($i > 0) {
  // //       $this->sql .= ", ";
  // //     }
  // //     $this->sql .= "{$column} = :{$column}";
  // //     $this->bindParams($column);
  // //     $i++;
  // //   }

  // //   if (!empty($this->where)) {
  // //     $this->sql .= " WHERE ";
  // //     $i = 0;
  // //     foreach ($this->where as $key => $value) {
  // //       if ($i > 0) {
  // //         $this->sql .= " AND ";
  // //       }
  // //       $this->sql .= "{$key} = :{$key}";
  // //       $this->bind($value);
  // //       $this->bindParams($key);
  // //       $i++;
  // //     }
  // //   }

  // //   $this->prepare($this->sql);

  // //   $i = 0;
  // //   foreach ($this->columns as $column) {
  // //     $this->statement->bindValue(":{$column}", $this->params[$i]);
  // //     $i++;
  // //   }

  // //   $this->execute();
  // //   return $this->results();
  // // }

  // /**
  //  * @param string $table
  //  * @param array $columns
  //  * @param array $where
  //  * @param string|null $intoClass
  //  * @return array
  //  */
  // // public function delete(string $table, array $columns = [], array $where = [], string $intoClass = null): array
  // // {
  // //   $this->table = $table;
  // //   $this->columns = $columns;
  // //   $this->where = $where;
  // //   $this->intoClass = $intoClass;

  // //   $this->sql = "DELETE FROM {$this->table}";

  // //   if (!empty($this->where)) {
  // //     $this->sql .= " WHERE ";
  // //     $i = 0;
  // //     foreach ($this->where as $key => $value) {
  // //       if ($i > 0) {
  // //         $this->sql .= " AND ";
  // //       }
  // //       $this->sql .= "{$key} = :{$key}";
  // //       $this->bind($value);
  // //       $this->bindParams($key);
  // //       $i++;
  // //     }
  // //   }

  // //   $this->prepare($this->sql);
  // //   return $this->results();
  // // }

  // /**
  //  * @param string $table
  //  * @param array $columns
  //  * @param array $where
  //  * @param string|null $intoClass
  //  * @return array
  //  */
  // public function truncate(string $table, array $columns = [], array $where = [], string $intoClass = null): array
  // {
  //   $this->table = $table;
  //   $this->columns = $columns;
  //   $this->where = $where;
  //   $this->intoClass = $intoClass;

  //   $this->sql = "TRUNCATE TABLE {$this->table}";

  //   $this->prepare($this->sql);
  //   return $this->results();
  // }

  // /**
  //  * @param string $table
  //  * @param array $columns
  //  * @param array $where
  //  * @param string|null $intoClass
  //  * @return array
  //  */

  // public function drop(string $table, array $columns = [], array $where = [], string $intoClass = null): array
  // {
  //   $this->table = $table;
  //   $this->columns = $columns;
  //   $this->where = $where;
  //   $this->intoClass = $intoClass;

  //   $this->sql = "DROP TABLE {$this->table}";

  //   $this->prepare($this->sql);
  //   return $this->results();
  // }

  // /**
  //  * @param string $table
  //  * @param array $columns
  //  * @param array $where
  //  * @param string|null $intoClass
  //  * @return array
  //  */
  // public function create(string $table, array $columns = [], array $where = [], string $intoClass = null): array
  // {
  //   $this->table = $table;
  //   $this->columns = $columns;
  //   $this->where = $where;
  //   $this->intoClass = $intoClass;

  //   $this->sql = "CREATE TABLE {$this->table} (";

  //   $i = 0;
  //   foreach ($this->columns as $column) {
  //     if ($i > 0) {
  //       $this->sql .= ", ";
  //     }
  //     $this->sql .= "{$column}";
  //     $i++;
  //   }

  //   $this->sql .= ")";

  //   $this->prepare($this->sql);
  //   return $this->results();
  // }

  // /**
  //  * @param string $table
  //  * @param array $columns
  //  * @param array $where
  //  * @param string|null $intoClass
  //  * @return array
  //  */
  // public function alter(string $table, array $columns = [], array $where = [], string $intoClass = null): array

  // {
  //   $this->table = $table;
  //   $this->columns = $columns;
  //   $this->where = $where;
  //   $this->intoClass = $intoClass;

  //   $this->sql = "ALTER TABLE {$this->table} ";

  //   $i = 0;
  //   foreach ($this->columns as $column) {
  //     if ($i > 0) {
  //       $this->sql .= ", ";
  //     }
  //     $this->sql .= "{$column}";
  //     $i++;
  //   }

  //   $this->prepare($this->sql);
  //   return $this->results();
  // }

  // /**
  //  * @param string $table
  //  * @param array $columns
  //  * @param array $where
  //  * @param string|null $intoClass
  //  * @return array
  //  */

  // public function rename(string $table, array $columns = [], array $where = [], string $intoClass = null): array
  // {
  //   $this->table = $table;
  //   $this->columns = $columns;
  //   $this->where = $where;
  //   $this->intoClass = $intoClass;

  //   $this->sql = "RENAME TABLE {$this->table} TO ";

  //   $i = 0;
  //   foreach ($this->columns as $column) {
  //     if ($i > 0) {
  //       $this->sql .= ", ";
  //     }
  //     $this->sql .= "{$column}";
  //     $i++;
  //   }

  //   $this->prepare($this->sql);
  //   return $this->results();
  // }
}
