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

    $stmt = $this->prepare("INSERT INTO {$this->tableName()} (" . implode(',', $attributes) . ") VALUES (" . implode(',', $params) . ")");
    foreach ($attributes as $attribute) {
      $stmt->bindValue(":$attribute", $this->{$attribute});
    }
    $stmt->execute();
    return true;
  }

  /**
   * @method find
   * @param int $id
   * @return mixed
   */
  public function find($id)
  {
    $stmt = $this->prepare("SELECT * FROM {$this->tableName()} WHERE {$this->primaryKey} = :id");
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    return $stmt->fetchObject(static::class);
  }

  /**
   * @method update
   * @param int $id
   * @return mixed
   */
  public function update($id)
  {
    $attributes = $this->attributes();
    $params = array_map(function ($attr) {
      return "{$attr} = :{$attr}";
    }, $attributes);

    $stmt = $this->prepare("UPDATE {$this->tableName()} SET " . implode(',', $params) . " WHERE {$this->primaryKey} = :id");
    foreach ($attributes as $attribute) {
      $stmt->bindValue(":$attribute", $this->{$attribute});
    }
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    return true;
  }

  /**
   * @method delete
   * @param int $id
   * @return mixed
   * @throws \Exception
   */
  public function delete($id)
  {
    $stmt = $this->prepare("DELETE FROM {$this->tableName()} WHERE {$this->primaryKey} = :id");
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    return true;
  }

  /**
   * @method select columns
   * @param array $columns
   * @return mixed
   */
  public function select($columns = ['*'])
  {
    $stmt = $this->prepare("SELECT " . implode(',', $columns) . " FROM {$this->tableName()}");
    $stmt->execute();
    return $stmt->fetchAll();
  }
}
