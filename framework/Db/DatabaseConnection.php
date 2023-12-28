<?php

namespace LaraCore\Framework\Db;

use LaraCore\Framework\Db\DbExceptions\DatabaseConnectionException;
use LaraCore\Framework\Db\Interfaces\DatabaseConnectionInterface;
use PDO;

class DatabaseConnection implements DatabaseConnectionInterface
{
  /**
   * @var PDO
   */
  protected $db;

  /**
   * @var array
   */
  protected $credentials = [];

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
   * Create a new database connection
   * 
   * @return PDO
   */
  public function open(): PDO
  {
    try {
      $params = [
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_PERSISTENT => true,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
      ];
      $this->db = new PDO(
        $this->credentials['dsn'],
        $this->credentials['username'],
        $this->credentials['password'],
        $params
      );
    } catch (\PDOException $e) {
      throw new DatabaseConnectionException($e->getMessage(), $e->getCode(), $e);
    }
    return $this->db;
  }

  /**
   * Close database connection
   * 
   * @return void
   */
  public function close(): void
  {
    $this->db = null;
  }
}