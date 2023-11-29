<?php

namespace LaraCore\Framework\Db\FluidOrm\EntityManager;

use LaraCore\Framework\Db\FluidOrm\EntityManager\CrudInterface;
use LaraCore\Framework\Db\FluidOrm\DataMapper\DataMapper;
use LaraCore\Framework\Db\FluidOrm\QueryBuilder\QueryBuilder;
use Throwable;

class Crud implements CrudInterface
{
  protected DataMapper $dataMapper;

  protected QueryBuilder $queryBuilder;

  protected string $tableSchema;

  protected string $tableSchemaID;

  protected array $options;

  /**
   * Summary of __construct
   * 
   * @param DataMapper $dataMapper
   * @param QueryBuilder $queryBuilder
   * @param string $tableSchema
   * @param string $tableSchemaID
   * @param ?array $options
   */
  public function __construct(DataMapper $dataMapper, QueryBuilder $queryBuilder, string $tableSchema, string $tableSchemaID, ?array $options = [])
  {
    $this->dataMapper = $dataMapper;
    $this->queryBuilder = $queryBuilder;
    $this->tableSchema = $tableSchema;
    $this->tableSchemaID = $tableSchemaID;
    $this->options = $options;
  }

  /**
   * @InheritDoc
   *
   * @return string
   */
  public function getSchema(): string
  {
    return (string) $this->tableSchema;
  }

  /**
   * @InheritDoc
   *
   * @return string
   */
  public function getSchemaID(): string
  {
    return (string) $this->tableSchemaID;
  }

  /**
   * @InheritDoc
   *
   * @return int
   */
  public function lastID(): int
  {
    return $this->dataMapper->getLastId();
  }

  /**
   * @InheritDoc
   *
   * @param array $fields
   * @return bool
   */
  public function create(array $fields): bool
  {
    try {
      $args = [
        'table' => $this->getSchema(),
        'fields' => $fields,
        'type' => 'insert'
      ];
      $query = $this->queryBuilder->buildQuery($args)->insertQuery();
      $this->dataMapper->persist($query, $this->dataMapper->builderQueryParameters($fields));
      if ($this->dataMapper->numRows() === 1) {
        return true;
      }
      return false;
    } catch (Throwable $throwable) {
      throw $throwable;
    }
  }

  /**
   * @InheritDoc
   *
   * @param array $selectors
   * @param array $constraints
   * @param array $parameters
   * @param array $optional
   * @return array
   */
  public function read(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []): array
  {
    try {
      $args = [
        'table' => $this->getSchema(),
        'type' => 'select',
        'selectors' => $selectors,
        'conditions' => $conditions,
        'params' => $parameters,
        'extras' => $optional
      ];
      $query = $this->queryBuilder->buildQuery($args)->selectQuery();
      $this->dataMapper->persist($query, $this->dataMapper->builderQueryParameters($conditions, $parameters));
      if ($this->dataMapper->numRows() > 0) {
        return $this->dataMapper->results();
      }
      return [];
    } catch (Throwable $throwable) {
      throw $throwable;
    }
  }

  /**
   * @InheritDoc
   *
   * @param array $fields
   * @param array $primary_key
   * @return bool
   */
  public function update(array $fields, string $primaryKey): bool
  {
    try {
      $args = [
        'table' => $this->getSchema(),
        'type' => 'update',
        'fields' => $fields,
        'primary_key' => $primaryKey
      ];
      $query = $this->queryBuilder->buildQuery($args)->updateQuery();
      $this->dataMapper->persist($query, $this->dataMapper->builderQueryParameters($fields));
      if ($this->dataMapper->numRows() === 1) {
        return true;
      }
      return false;
    } catch (Throwable $throwable) {
      throw $throwable;
    }
  }

  /**
   * @InheritDoc
   *
   * @param array $conditions
   * @return bool
   */
  public function delete(array $conditions = []): bool
  {
    try {
      $args = [
        'table' => $this->getSchema(),
        'type' => 'delete',
        'conditions' => $conditions
      ];
      $query = $this->queryBuilder->buildQuery($args)->deleteQuery();
      $this->dataMapper->persist($query, $this->dataMapper->builderQueryParameters($conditions));
      if ($this->dataMapper->numRows() === 1) {
        return true;
      }
      return false;
    } catch (Throwable $throwable) {
      throw $throwable;
    }
  }

  /**
   * @InheritDoc
   *
   * @param array $selectors
   * @param array $conditions
   * @param array $optional
   * @return array
   */
  public function search(array $selectors = [], array $conditions = []): array
  {
    try {
      $args = [
        'table' => $this->getSchema(),
        'type' => 'search',
        'selectors' => $selectors,
        'conditions' => $conditions
      ];
      $query = $this->queryBuilder->buildQuery($args)->searchQuery();
      $this->dataMapper->persist($query, $this->dataMapper->builderQueryParameters($conditions));
      if ($this->dataMapper->numRows() > 0) {
        return $this->dataMapper->results();
      }
      return [];
    } catch (Throwable $throwable) {
      throw $throwable;
    }
  }

  /**
   * @inheritDoc
   * 
   * @param string $rowQuery
   * @param array|null $conditions
   * @return array
   */
  public function rowQuery(string $rowQuery, ?array $conditions = []): array
  {
    try {
      $args = [
        'table' => $this->getSchema(),
        'type' => 'row',
        'row_query' => $rowQuery,
        'conditions' => $conditions
      ];
      $query = $this->queryBuilder->buildQuery($args)->rowQuery();
      $this->dataMapper->persist($query, $this->dataMapper->builderQueryParameters($conditions));
      if ($this->dataMapper->numRows() > 0) {
        return $this->dataMapper->results();
      }
      return [];
    } catch (Throwable $throwable) {
      throw $throwable;
    }
  }
}