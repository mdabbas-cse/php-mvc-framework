<?php

namespace Lora\Core\Framework\Db;

use Lora\Core\Framework\Db\Connection;
use Lora\Core\Framework\Db\QueryBuilder;
use Lora\Core\Framework\Configuration;

abstract class Model
{
  protected $db;
  public $errors = [];

  public function __construct()
  {
    $this->db = new QueryBuilder(Connection::make(Configuration::get('database')));
  }

  /**
   * get all data from database
   */
  public function all($tableName)
  {
    return $this->db->selectAll($tableName);
  }

  public function prepare($sql)
  {
    return $this->db->prepare($sql);
  }
}
