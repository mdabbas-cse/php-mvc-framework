<?php

namespace Lora\Core\Framework\Db\FluidOrm\Interfaces;

interface DataMapperInterface
{
  /**
   * @param string $sql
   * @return \PDOStatement
   */
  public function prepare(string $sql): self;

  /**
   * @param string $value
   */
  public function bind($value);

  /**
   * @param string $field
   * @param bool $isSearch
   * @return $this
   */
  public function bindParameters(array $fields, bool $isSearch = false): self;

  /**
   * @param string $field
   * @return $this
   */
  // public function bindSearchValues(array $fields): \PDOStatement;

  /**
   * @param string $field
   * @return $this
   */
  // public function bindValues(array $fields): \PDOStatement;

  /**
   * @param int 
   */
  public function numRows(): int;

  /**
   * @return void
   */
  public function execute(): void;

  /**
   * @return Object
   */
  public function result(): Object;

  /**
   * @return array
   */
  public function results(): array;


  /**
   * @method getLastId
   * @return int
   */
  public function getLastId(): int;

  /**
   * @param string $table
   * @param array $columns
   * @param array $where
   * @param string|null $intoClass
   * @return array
   */
  // public function select(string $table, array $columns = ['*'], array $where = [], string $intoClass = null): array;

  /**
   * @param string $table
   * @param array $columns
   * @param array $where
   * @param string|null $intoClass
   * @return array
   */
  // public function all(string $table, string $intoClass = null): array;

  /**
   * @param string $table
   * @param array $data
   * @return array
   */
  // public function insert(string $table, array $columns = [], array $where = [], string $intoClass = null): array;

  /**
   * @param string $table
   * @param array $data
   * @param array $where
   * @return bool
   */
  // public function update(string $table, array $data, array $where): bool;

  /**
   * @param string $table
   * @param array $where
   * @return bool
   */
  // public function delete(string $table, array $where): bool;

  /**
   * @param string $table
   * @param array $data
   * @return bool
   */
  // public function insertOrUpdate(string $table, array $data): bool;

  /**
   * @param string $table
   * @param array $data
   * @return bool
   */
  // public function insertIgnore(string $table, array $data): bool;

  /**
   * @param string $table
   * @param array $data
   * @return bool
   */
  // public function replace(string $table, array $data): bool;

  /**
   * @param array $data
   * @return mixed
   */
  // public function hydrate(array $data);

  /**
   * @return array
   */
  // public function extract(): array;

  /**
   * @return array
   */
  // public function getColumns(): array;

  /**
   * @return string
   */
  // public function getTableName(): string;

  /**
   * @return string
   */
  // public function getPrimaryKey(): string;

  /**
   * @return string
   */
  // public function getForeignKey(): string;

  /**
   * @return string
   */
  // public function getClassName(): string;

  /**
   * @return string
   */
  // public function getNamespace(): string;
}
