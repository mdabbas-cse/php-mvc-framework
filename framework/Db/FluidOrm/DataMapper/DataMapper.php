<?php

namespace Lora\Core\Framework\Db\FluidOrm\DataMapper;

use Lora\Core\Framework\Db\ExceptionTraits\InvalidArgumentException;
use Lora\Core\Framework\Db\FluidOrm\Exceptions\DataMapperException;
use Lora\Core\Framework\Db\FluidOrm\DataMapper\DataMapperInterface;
use PDOStatement;
use Throwable;

class DataMapper implements DataMapperInterface
{
  use InvalidArgumentException;
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
   * @InheritDoc
   * 
   * @return self
   */
  public function prepare(string $sql): self
  {
    $this->sql = $sql;
    $this->statement = $this->pdo->prepare($sql);
    return $this;
  }

  /**
   * @InheritDoc
   * 
   * @return
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
   * 
   * @Exception DataMapperException
   * @return PDOStatement
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
   * 
   * @Exception DataMapperException
   * @return PDOStatement
   */
  protected function bindSearchValues(array $fields): PDOStatement
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
   * 
   * @return PDOStatement
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
   * 
   * @return Int
   */
  public function numRows(): int
  {
    if ($this->statement)
      return $this->statement->rowCount();
  }

  /**
   * @InheritDoc
   * 
   * @return 
   */
  public function execute()
  {
    if ($this->statement)
      return $this->statement->execute();
  }

  /**
   * @InheritDoc
   * 
   * @return Object
   */
  public function result(): Object
  {
    if ($this->statement)
      return $this->statement->fetchObject();
  }

  /**
   * @InheritDoc
   * 
   * @return array
   */
  public function results(): array
  {
    if ($this->statement)
      return $this->statement->fetchAll(\PDO::FETCH_CLASS, $this->intoClass);
  }

  /**
   * @InheritDoc
   * 
   * @return int
   */
  public function getLastId(): int
  {
    try {
      $lastId =  $this->pdo->lastInsertId();
      if (!empty($lastId)) {
        return intval($lastId);
      }
      return 0;
    } catch (Throwable $throwable) {
      throw new DataMapperException($throwable->getMessage(), $throwable->getCode(), $throwable);
    }
  }

  public function builderQueryParameters(array $conditions = [], array $parameters = [])
  {
    return (!empty($conditions) || !empty($parameters)) ? array_merge($conditions, $parameters) : $parameters;
  }

  public function persist(string $sqlQuery, array $parameters = [])
  {
    try {
      $this->prepare($sqlQuery);
      $this->bindParameters($parameters);
      $this->execute();
    } catch (Throwable $throwable) {
      throw new DataMapperException($throwable->getMessage(), $throwable->getCode(), $throwable);
    }
  }
}
