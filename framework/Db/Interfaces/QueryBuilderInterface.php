<?php

/**
 * @package Lora
 */

namespace LaraCore\Framework\Db\Interfaces;

interface QueryBuilderInterface
{
  /**
   * @param string $table
   * @param string|null $intoClass
   * @return array
   */
  public function selectAll($table, $intoClass = null);

  /**
   * @param string $sql
   * @return \PDOStatement
   */
  public function prepare($sql): \PDOStatement;

  /**
   * @param string $table
   * @param array $columns
   * @param array $where
   * @param string|null $intoClass
   * @return array
   */
  public function select($table, $columns = ['*'], $where = [], $intoClass = null);
}
