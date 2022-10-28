<?php

namespace MVC\Framework;

use MVC\Framework\Db\Connection;
use MVC\Framework\Db\QueryBuilder;
use MVC\Framework\Configuration;

abstract class Model
{
  protected $db;
  public $errors = [];

  public function __construct()
  {
    $this->db = new QueryBuilder(Connection::make(Configuration::get('database')));
  }

  public function loadData($data)
  {
    foreach ($data as $key => $value) {
      if (property_exists($this, $key)) {
        $this->{$key} = $value;
      }
    }
  }
}
