<?php

namespace Lora\Core\Framework\Db\FluidOrm\DataMapper;

use Lora\Core\Framework\BaseExceptions\BaseUnexpectedValueException;
use Lora\Core\Framework\Db\FluidOrm\Interfaces\DatabaseConnectionInterface;
use Lora\Core\Framework\Db\FluidOrm\Interfaces\DataMapperInterface;

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
   * @method create
   * To create a new instance of DataMapper
   * 
   * @param string $driver
   * @param array $credentials
   * @return DataMapper
   */

  public function create(string $databaseConnectionString, Object $dataMapperEnvironmentConfiguration): DataMapperInterface
  {
    // Create databaseConnection Object and pass the database credentials in
    // $credentials = $dataMapperEnvironmentConfiguration->getDatabaseCredentials(YamlConfig::file('app')['pdo_driver']);
    $credentials = $dataMapperEnvironmentConfiguration->getDatabaseCredentials('mysql');
    $databaseConnectionObject = new $databaseConnectionString($credentials);
    if (!$databaseConnectionObject instanceof DatabaseConnectionInterface) {
      throw new BaseUnexpectedValueException($databaseConnectionString . ' is not a valid database connection object');
    }
    return new DataMapper($databaseConnectionObject);
  }
}
