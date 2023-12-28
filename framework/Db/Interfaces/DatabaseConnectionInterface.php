<?php

namespace LaraCore\Framework\Db\Interfaces;

use PDO;

interface DatabaseConnectionInterface
{
  /**
   * Create a new database connection
   * 
   * @return PDO
   */
  public function open(): PDO;

  /**
   * Close database connection
   */
  public function close(): void;
}