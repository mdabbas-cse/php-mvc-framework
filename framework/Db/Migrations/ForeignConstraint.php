<?php

namespace Lora\Core\Framework\Db\Migrations;


class ForeignConstraint
{

  protected $localColumn;
  protected $parent;
  public function __construct($localColumn, $parent)
  {
    $this->localColumn = $localColumn;
    $this->parent = $parent;
  }

  /**
   * Specify the referenced column.
   *
   * @param string $foreignColumn
   * @return $this
   */
  public function references($foreignColumn)
  {
    $this->parent->currentForeign->foreignColumn = $foreignColumn;
    return $this;
  }

  /**
   * Specify the referenced table.
   *
   * @param string $foreignTable
   * @return $this
   */
  public function on($foreignTable)
  {
    $constraintName = "{$this->localColumn}_{$foreignTable}_foreign";
    $this->parent->columns[] = "CONSTRAINT {$constraintName} FOREIGN KEY ({$this->localColumn}) REFERENCES {$foreignTable}({$this->parent->currentForeign->foreignColumn})";

    return $this->parent;
  }
}