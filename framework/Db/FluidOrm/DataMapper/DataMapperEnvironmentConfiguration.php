<?php

namespace LaraCore\Framework\Db\FluidOrm\DataMapper;

use LaraCore\Framework\Db\FluidOrm\Exceptions\DataMapperInvalidArgumentException;

class DataMapperEnvironmentConfiguration
{
  /**
   * @var array
   */
  private $credentials = [];

  /**
   * Main constructor Class
   * @param array $credentials
   * @return void
   */
  public function __construct(array $credentials)
  {
    $this->credentials = $credentials;
  }

  /**
   * @method getDatabaseCredentials
   * To get user define database credentials
   * @param string $driver
   * @return array
   */
  public function getDatabaseCredentials(string $driver): array
  {
    $credentialArray = [];

    foreach ($this->credentials as $credential) {
      if (array_key_exists($driver, $credential)) {
        $credentialArray = $credential[$driver];
      }
    }
    return $credentialArray;
  }

  /**
   * @method isCredentialValid
   * To check if user define database credentials is valid
   * @param array $driver
   * @return void
   */
  public function isCredentialValid(string $driver): void
  {
    if (empty($driver) && !is_string($driver)) {
      throw new DataMapperInvalidArgumentException('Invalid driver name. there is either no driver name or the driver name is not a string');
    }
    if (!is_array($this->credentials)) {
      throw new DataMapperInvalidArgumentException('Invalid credentials. The credentials must be an array');
    }
    if (!in_array($driver, array_keys($this->credentials))) {
      throw new DataMapperInvalidArgumentException('Invalid driver name. The driver name does not exist in the credentials array');
    }
  }
}
