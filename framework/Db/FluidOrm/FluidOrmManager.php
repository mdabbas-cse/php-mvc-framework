<?php

namespace Framework\Db\FluidOrm;

use Framework\Db\FluidOrm\EntityManager\Crud;
use Framework\Db\FluidOrm\EntityManager\EntityManagerFactory;
use Lora\Core\Framework\Db\DatabaseConnection;
use Lora\Core\Framework\Db\FluidOrm\DataMapper\DataMapperEnvironmentConfiguration;
use Lora\Core\Framework\Db\FluidOrm\DataMapper\DataMapperFactory;
use Lora\Core\Framework\Db\FluidOrm\QueryBuilder\QueryBuilder;
use Lora\Core\Framework\Db\FluidOrm\QueryBuilder\QueryBuilderFactory;

class FluidOrmManager
{
  protected DataMapperEnvironmentConfiguration $environmentConfiguration;

  protected string $tableSchema;

  protected string $tableSchemaID;

  protected array $options;

  /**
   * Summary of __construct
   * 
   * @param DataMapperEnvironmentConfiguration $environmentConfiguration
   * @param string $tableSchema
   * @param string $tableSchemaID
   * @param ?array $options
   */

  public function __construct(DataMapperEnvironmentConfiguration $environmentConfiguration, string $tableSchema, string $tableSchemaID, ?array $options = [])
  {
    $this->environmentConfiguration = $environmentConfiguration;
    $this->tableSchema = $tableSchema;
    $this->tableSchemaID = $tableSchemaID;
    $this->options = $options;
  }

  /**
   * @method for initialize the DataMapperFactory
   */

  public function initialize()
  {
    $dataMapperFactory = new DataMapperFactory();
    $dataMapper = $dataMapperFactory->create(DatabaseConnection::class, DataMapperEnvironmentConfiguration::class);

    if ($dataMapper) {
      $queryBuilderFactory = new QueryBuilderFactory();
      $queryBuilder = $queryBuilderFactory->create(QueryBuilder::class);
      if ($queryBuilder) {
        $entityManager = new EntityManagerFactory($dataMapper, $queryBuilder);
        return $entityManager->create(Crud::class, $this->tableSchema, $this->tableSchemaID, $this->options);
      }
    }
  }
}
