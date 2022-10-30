<?php

/**
 * abstract class DataModel
 * like ORM in Laravel
 * @package MVC\Framework\Db
 */

namespace MVC\Framework\Db;

abstract class DataModel extends Model
{
  protected $table;
  protected $fillable = [];
  protected $primaryKey = 'id';

  public function __construct()
  {
    parent::__construct();
  }

  // public function tableName()
  // {
  //   return strtolower(get_called_class());
  // }
  /**
   * assign table name from class name
   */
  abstract public function tableName(): string;

  abstract public function attributes(): array;

  public function loadData($data)
  {
    foreach ($data as $key => $value) {
      if (!property_exists($this, $key)) {
        $this->{$key} = $value;
      }
    }
  }

  /**
   * assign dynamic properties
   */
  public function __set($name, $value)
  {
    $this->{$name} = $value;
  }

  /**
   * save data to database
   */
  public function save()
  {
    $attributes = $this->attributes();
    $params = array_map(function ($attr) {
      return ":{$attr}";
    }, $attributes);

    $statement = $this->prepare("INSERT INTO {$this->tableName()} (" . implode(',', $attributes) . ") VALUES (" . implode(',', $params) . ")");
    foreach ($attributes as $attribute) {
      $statement->bindValue(":$attribute", $this->{$attribute});
    }
    $statement->execute();
    return true;
  }
}
