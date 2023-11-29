<?php

namespace LaraCore\Framework\Db\FluidOrm\DataRepository;

use LaraCore\Framework\Db\FluidOrm\DataRepository\Exceptions\DataRepositoryException;

class DataRepositoryFactory
{
  protected string $tableSchema;
  protected string $tableSchemaID;

  protected string $crudIdentifier;

  /**
   * DataRepositoryFactory constructor.
   * 
   * @param string $crudIdentifier
   * @param string $tableSchema
   * @param string $tableSchemaID
   */

  public function __construct(string $crudIdentifier, string $tableSchema, string $tableSchemaID)
  {
    $this->crudIdentifier = $crudIdentifier;
    $this->tableSchema = $tableSchema;
    $this->tableSchemaID = $tableSchemaID;
  }

  /**
   * @return string
   */
  public function create(string $dataRepositoryString)
  {
    $dataRepositoryObject = new $dataRepositoryString();

    if (!$dataRepositoryObject instanceof DataRepositoryInterface) {
      throw new DataRepositoryException($dataRepositoryObject . ' is not an instance of DataRepositoryInterface');
    }
    return $dataRepositoryObject;
  }
}