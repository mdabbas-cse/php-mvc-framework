<?php

/**
 * class for sql blueprint
 */

namespace LaraCore\Framework\Db\Migrations;

use LaraCore\Framework\Db\Migrations\ForeignConstraint;

/**
 * Class Blueprint
 * @package Your\Namespace\Migrations
 *
 * The Blueprint class provides methods for defining database table columns
 * and their properties during the migration process.
 */
final class Blueprint
{
  /**
   * @var array List of column definitions
   */
  protected $columns = [];

  /**
   * @var string|null The current column being defined
   */
  protected $currentColumn;

  /**
   * @var bool Whether the current column is nullable
   */
  protected $isCurrentColumnNullable = false;

  /**
   * @var mixed|null Default value for the current column
   */
  protected $currentColumnDefault;

  /**
   * @var ForeignConstraint|null|$this The current foreign constraint being defined
   */
  protected $currentForeign;

  /**
   * @var bool Whether the current column should be unique
   */
  protected $currentColumnUnique = false;

  /**
   * Define an auto-incrementing integer column.
   *
   * @param string $columnName
   * @return $this
   */
  public function id($columnName = 'id')
  {
    $this->columns[] = "`{$columnName}` INT PRIMARY KEY AUTO_INCREMENT NOT NULL";
    return $this;
  }

  /**
   * Define an auto-incrementing integer column.
   *
   * @param string $columnName
   * @return $this
   */
  public function increments($columnName)
  {
    $this->columns[] = "$columnName INT PRIMARY KEY AUTO_INCREMENT NOT NULL";
    return $this;
  }

  /**
   * Define a big integer (8-byte) auto-incrementing column.
   *
   * @param string $columnName
   * @return $this
   */
  public function bigIncrements($columnName)
  {
    $this->columns[] = "$columnName BIGINT PRIMARY KEY AUTO_INCREMENT NOT NULL";
    return $this;
  }

  /**
   * Define an integer column.
   *
   * @param string $columnName
   * @return $this
   */
  public function integer($columnName)
  {
    $this->setCurrentColumn($columnName);
    $this->columns[] = $this->getCurrentColumnDefinition('INT');
    return $this;
  }

  /**
   * Define a big integer (8-byte) column.
   *
   * @param string $columnName
   * @return $this
   */
  public function bigInteger($columnName)
  {
    $this->setCurrentColumn($columnName);
    $this->columns[] = $this->getCurrentColumnDefinition('BIGINT');
    return $this;
  }

  /**
   * Define a string column.
   *
   * @param string $columnName
   * @param int $length
   * @return $this
   */
  public function string($columnName, $length = 255)
  {
    $this->setCurrentColumn($columnName);
    $this->columns[] = $this->getCurrentColumnDefinition("VARCHAR($length)");
    return $this;
  }

  /**
   * Define a text column.
   *
   * @param string $columnName
   * @return $this
   */
  public function text($columnName)
  {
    $this->setCurrentColumn($columnName);
    $this->columns[] = $this->getCurrentColumnDefinition('TEXT');
    return $this;
  }

  /**
   * Define a float column.
   *
   * @param string $columnName
   * @param int $total
   * @param int $places
   * @return $this
   */
  public function float($columnName, $total = 8, $places = 2)
  {
    $this->setCurrentColumn($columnName);
    $this->columns[] = $this->getCurrentColumnDefinition("FLOAT($total, $places)");
    return $this;
  }

  /**
   * Define a double column.
   *
   * @param string $columnName
   * @param int $total
   * @param int $places
   * @return $this
   */
  public function double($columnName, $total = 8, $places = 2)
  {
    $this->setCurrentColumn($columnName);
    $this->columns[] = $this->getCurrentColumnDefinition("DOUBLE($total, $places)");
    return $this;
  }

  /**
   * Define a decimal column.
   *
   * @param string $columnName
   * @param int $total
   * @param int $places
   * @return $this
   */
  public function decimal($columnName, $total = 8, $places = 2)
  {
    $this->setCurrentColumn($columnName);
    $this->columns[] = $this->getCurrentColumnDefinition("DECIMAL($total, $places)");
    return $this;
  }

  /**
   * Define a boolean column.
   *
   * @param string $columnName
   * @return $this
   */
  public function boolean($columnName)
  {
    $this->setCurrentColumn($columnName);
    $this->columns[] = $this->getCurrentColumnDefinition('BOOLEAN');
    return $this;
  }

  /**
   * Define a date column.
   *
   * @param string $columnName
   * @return $this
   */
  public function date($columnName)
  {
    $this->setCurrentColumn($columnName);
    $this->columns[] = $this->getCurrentColumnDefinition('DATE');
    return $this;
  }

  /**
   * Define a datetime column.
   *
   * @param string $columnName
   * @return $this
   */
  public function dateTime($columnName)
  {
    $this->setCurrentColumn($columnName);
    $this->columns[] = $this->getCurrentColumnDefinition('DATETIME');
    return $this;
  }

  /**
   * Define a time column.
   *
   * @param string $columnName
   * @return $this
   */
  public function time($columnName)
  {
    $this->setCurrentColumn($columnName);
    $this->columns[] = $this->getCurrentColumnDefinition('TIME');
    return $this;
  }

  /**
   * Define a timestamp column.
   *
   * @param string $columnName
   * @return $this
   */
  public function timestamp($columnName)
  {
    $this->setCurrentColumn($columnName);
    $this->columns[] = $this->getCurrentColumnDefinition('TIMESTAMP');
    return $this;
  }

  /**
   * Specify that the current column allows NULL values.
   *
   * @return $this
   */
  public function nullable()
  {
    $this->isCurrentColumnNullable = true;
    return $this;
  }

  /**
   * Specify a default value for the current column.
   *
   * @param mixed $value
   * @return $this
   */
  public function default($value)
  {
    $this->currentColumnDefault = $value;
    return $this;
  }

  /**
   * Specify that the current column should be unique.
   *
   * @return $this
   */
  public function unique()
  {
    $this->currentColumnUnique = true;
    return $this;
  }

  /**
   * Specify the creation and update timestamps for the table.
   *
   * @return $this
   */
  public function timestamps()
  {
    $this->columns[] = "`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL, `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL";
    return $this;
  }

  /**
   * Get the columns array.
   *
   * @return array
   */
  public function getColumns()
  {
    return $this->columns;
  }

  /**
   * Set the current column being defined.
   *
   * @param string $columnName
   */
  protected function setCurrentColumn($columnName)
  {
    $this->currentColumn = "`{$columnName}`";
    $this->isCurrentColumnNullable = false;
    $this->currentColumnDefault = null;
    $this->currentColumnUnique = false;
  }

  /**
   * Get the definition for the current column.
   *
   * @param string $type
   * @return string
   */
  protected function getCurrentColumnDefinition($type): string
  {
    $definition = "$this->currentColumn $type";

    if ($this->isCurrentColumnNullable) {
      $definition .= ' NULL';
    } else {
      $definition .= ' NOT NULL';
    }

    if ($this->currentColumnDefault !== null) {
      $definition .= " DEFAULT " . $this->quoteDefault($this->currentColumnDefault);
    }

    if ($this->currentColumnUnique) {
      $definition .= ' UNIQUE';
    }

    return $definition;
  }

  /**
   * Specify add a new column to the table after a given column.
   * 
   * @param string $columnName
   * @return $this
   */
  public function after($columnName)
  {
    $this->columns[] = "AFTER $columnName";
    return $this;
  }

  /**
   * Specify add a new column to the table first.
   * 
   * @return $this
   */
  public function first()
  {
    $this->columns[] = "FIRST";
    return $this;
  }

  /**
   * Specify add a new column to the table.
   * 
   * @param string $columnName
   * @return $this
   */
  public function addColumn($columnName)
  {
    $this->columns[] = "ADD COLUMN $columnName";
    return $this;
  }

  /**
   * Specify drop a column from the table.
   * 
   * @param string $columnName
   * @return $this
   */
  public function dropColumn($columnName)
  {
    $this->columns[] = "DROP COLUMN $columnName";
    return $this;
  }

  /**
   * Specify modify a column from the table.
   * 
   * @param string $columnName
   * @return $this
   */
  public function modifyColumn($columnName)
  {
    $this->columns[] = "MODIFY COLUMN $columnName";
    return $this;
  }

  /**
   * Specify rename a column from the table.
   * 
   * @param string $columnName
   * @return $this
   */
  public function renameColumn($columnName)
  {
    $this->columns[] = "RENAME COLUMN $columnName";
    return $this;
  }

  /**
   * Specify rename a table.
   * 
   * @param string $tableName
   * @return $this
   */
  public function renameTable($tableName)
  {
    $this->columns[] = "RENAME TO $tableName";
    return $this;
  }

  /**
   * Specify drop a table.
   * 
   * @param string $tableName
   * @return $this
   */
  public function dropTable($tableName)
  {
    $this->columns[] = "DROP TABLE $tableName";
    return $this;
  }

  /**
   * Specify add a primary key to the table.
   * 
   * @param string $columnName
   * @return $this
   */
  public function addPrimaryKey($columnName)
  {
    $this->columns[] = "ADD PRIMARY KEY ($columnName)";
    return $this;
  }

  /**
   * Specify drop a primary key from the table.
   * 
   * @param string $columnName
   * @return $this
   */
  public function dropPrimaryKey($columnName)
  {
    $this->columns[] = "DROP PRIMARY KEY ($columnName)";
    return $this;
  }

  /**
   * Specify add a unique key to the table.
   * 
   * @param string $columnName
   * @return $this
   */
  public function addUniqueKey($columnName)
  {
    $this->columns[] = "ADD UNIQUE ($columnName)";
    return $this;
  }

  /**
   * Specify drop a unique key from the table.
   * 
   * @param string $columnName
   * @return $this
   */
  public function dropUniqueKey($columnName)
  {
    $this->columns[] = "DROP UNIQUE ($columnName)";
    return $this;
  }

  /**
   * Specify add a index to the table.
   * 
   * @param string $columnName
   * @return $this
   */
  public function addIndex($columnName)
  {
    $this->columns[] = "ADD INDEX ($columnName)";
    return $this;
  }

  /**
   * Specify drop a index from the table.
   * 
   * @param string $columnName
   * @return $this
   */
  public function dropIndex($columnName)
  {
    $this->columns[] = "DROP INDEX ($columnName)";
    return $this;
  }

  /**
   * Specify add a foreign key to the table.
   * 
   * @param string $columnName
   * @return $this
   */
  public function foreign($localColumn)
  {
    $this->currentForeign = new ForeignConstraint($localColumn, $this);
    return $this->currentForeign;
  }

  /**
   * Specify drop a foreign key from the table.
   * 
   * @param string $columnName
   * @return $this
   */
  public function dropForeign($columnName)
  {
    $this->columns[] = "DROP FOREIGN KEY ($columnName)";
    return $this;
  }


  /**
   * Specify references 
   */

  /**
   * Specify add a comment to the table.
   * 
   * @param string $columnName 
   * @return $this
   */
  public function comment($columnName)
  {
    $this->columns[] = "COMMENT $columnName";
    return $this;
  }
  /**
   * Quote the default value.
   *
   * @param mixed $value
   * @return string
   */
  protected function quoteDefault($value)
  {
    if (is_string($value)) {
      return "'" . addslashes($value) . "'";
    } elseif ($value === null) {
      return 'NULL';
    } else {
      return $value;
    }
  }
}