<?php

namespace LaraCore\Framework\Db\FluidOrm\DataRepository;

interface DataRepositoryInterface
{

  /**
   * Find and return an item by its ID
   * 
   * @param int $id
   * @return array
   */
  public function find(int $id): array;

  /**
   * Find and return all table rows as an array
   * 
   * @return array
   */
  public function findAll(): array;

  /**
   * Find and return 1 or more rows by various argument which are optional by default
   * 
   * @param array $selectors = []
   * @param array $conditions = []
   * @param array $parameters = []
   * @param array $optional = []
   * @return array
   */
  public function findBy(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []): array;

  /**
   * Find and Return 1 row by the method argument
   * 
   * @param array $conditions
   * @return array
   */
  public function findOneBy(array $conditions): array;

  /**
   * Returns a single row from the storage table as an object
   * 
   * @param array $conditions
   * @param array $selectors
   * @return Object
   */
  public function findObjectBy(array $conditions = [], array $selectors = []): object;

  /**
   * Returns the search results based on the user search conditions and parameters
   * 
   * @param array $selectors = []
   * @param array $conditions = []
   * @param array $parameters = []
   * @param array $optional = []
   * @return array
   * @throws \Framework\Db\FluidOrm\DataRepository\Exceptions\InvalidDataRepositoryException
   */
  public function findBySearch(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []): array;

  /**
   * Find and delete a row by its ID from storage device
   * 
   * @param array $condition
   * @return bool
   */
  public function findByIdAndDelete(array $conditions): bool;

  /**
   * Update the queried row and return true on success. We can use the second argument
   * to specify which column to update by within the where clause
   * 
   * @param array $fields
   * @param int id
   * @return bool
   */
  public function findByIdAndUpdate(array $fields = [], int $id): bool;

  /**
   * Returns the storage data as an array along with formatted paginated results. This method
   * will also returns queried search results
   * 
   * @param array $args
   * @param Object $request
   * @return array
   */
  public function findWithSearchAndPaging(object $request, array $args = []): array;

  /**
   * Find and item by its ID and return the object row else return 404 with the or404 chaining method
   * 
   * @param int $id
   * @param array $selectors
   * @return self
   */
  public function findAndReturn(int $id, array $selectors = []): self;

  /**
   * Returns 404 error page if the findAndReturn method or property returns empty or null
   *
   * @return Object|null
   */
  // public function or404();
}