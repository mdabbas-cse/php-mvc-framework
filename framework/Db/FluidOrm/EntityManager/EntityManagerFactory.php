<?php


namespace Framework\Db\FluidOrm\EntityManager;

use Framework\Db\FluidOrm\EntityManager\Exceptions\CrudException;
use Lora\Core\Framework\Db\FluidOrm\Interfaces\DataMapperInterface;
use Lora\Core\Framework\Db\FluidOrm\QueryBuilder\QueryBuilderInterface;

class EntityManagerFactory
{

  protected DataMapperInterface $dataMapper;

  protected QueryBuilderInterface $queryBuilder;

  /**
   * Main constructor
   * 
   * @param DataMapperInterface $dataMapper
   * @param QueryBuilderInterface $queryBuilder
   */
  public function __construct(DataMapperInterface $dataMapper, QueryBuilderInterface $queryBuilder)
  {
    $this->dataMapper = $dataMapper;
    $this->queryBuilder = $queryBuilder;
  }

  /**
   * Create entity manager
   * 
   * @param string $crudSting
   * @param string $tableSchema
   * @param string $tableSchemaID
   * @param array $options
   * @throws CrudException
   * @return EntityManager
   */
  public function create(string $crudSting, string $tableSchema, string $tableSchemaID, array $options = []): EntityManagerInterface
  {
    $curdObject = new $crudSting($this->dataMapper, $this->queryBuilder, $tableSchema, $tableSchemaID, $options);
    if (!$curdObject instanceof CrudInterface)
      throw new CrudException("{$crudSting} must be instance of CrudInterface");

    return new EntityManager($curdObject);
  }
}
