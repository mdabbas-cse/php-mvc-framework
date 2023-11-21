<?php

namespace LaraCore\Framework\Db\FluidOrm\DataMapper;

use LaraCore\Framework\BaseExceptions\BaseUnexpectedValueException;
use LaraCore\Framework\Db\FluidOrm\Interfaces\DatabaseConnectionInterface;
use LaraCore\Framework\Db\FluidOrm\Interfaces\DataMapperInterface;

class DataMapperFactory
{

  /**
   * Main constructor Class
   * 
   * return void
   */
  public function __construct()
  {
  }

  /**
   * Create data mapper
   * 
   * @param string $databaseConnection
   * @param string $environmentConfiguration
   * @throws BaseUnexpectedValueException
   * @return DataMapperInterface
   */

  public function create(string $databaseConnectionString, string $dataMapperEnvironmentConfiguration): DataMapperInterface
  {
    // Create databaseConnection Object and pass the database credentials in
    // $credentials = $dataMapperEnvironmentConfiguration->getDatabaseCredentials(YamlConfig::file('app')['pdo_driver']);
    $credentials = (new $dataMapperEnvironmentConfiguration([]))->getDatabaseCredentials('mysql');
    $databaseConnectionObject = new $databaseConnectionString($credentials);
    if (!$databaseConnectionObject instanceof DatabaseConnectionInterface) {
      throw new BaseUnexpectedValueException($databaseConnectionString . ' is not a valid database connection object');
    }
    return new DataMapper($databaseConnectionObject);
  }
}
