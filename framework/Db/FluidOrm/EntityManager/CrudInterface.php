<?php

namespace LaraCore\Framework\Db\FluidOrm\EntityManager;

interface CrudInterface
{
  /**
   * Returns the storage schema name as string
   * 
   * @return string
   */
  public function getSchema(): string;

  /**
   * Returns the primary key for the storage schema
   * 
   * @return string
   */
  public function getSchemaID(): string;

  /**
   * Returns the last inserted ID
   * 
   * @return int
   */
  public function lastID(): int;

  /**
   * Create method which inserts data within a storage table
   * 
   * @param array $fields
   * @return bool
   */
  public function create(array $fields): bool;

  /**
   * Returns a an array of database rows based on the individual supplied arguments
   * 
   * @param array $selectors = []
   * @param array $conditions = []
   * @param array $parameters = []
   * @param array $optional = []
   * @return array
   */
  public function read(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []): array;

  /**
   * Update method which update 1 or more rows of data with in the storage table
   * 
   * @param array $fields
   * @param string $primaryKey
   * @return bool
   */
  public function update(array $fields, string $primaryKey): bool;

  /**
   * Delete method which will permanently delete a row from the storage table
   * 
   * @param array $conditions
   * @return bool
   */
  public function delete(array $conditions = []): bool;

  /**
   * Search method which returns queried search results
   * 
   * @param array $selectors = []
   * @param array $conditions = []
   * @return null|array
   */
  public function search(array $selectors = [], array $conditions = []): array;

  /**
   * Returns a custom query string. The second argument can assign and associate array
   * of conditions for the query string
   * 
   * @param string $rawQuery
   * @param array|null $conditions
   * @param string $resultType
   * @return mixed
   */
  public function rowQuery(string $rowQuery, array $conditions = []): array;
}