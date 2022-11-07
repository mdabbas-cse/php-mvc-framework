<?php

namespace Lora\Core\Framework\Db\FluidOrm\QueryBuilder;

interface QueryBuilderInterface
{
  /**
   * @method insertQuery
   * @return string
   */
  public function insertQuery(): string;

  /**
   * @method updateQuery
   * @return string
   */
  public function updateQuery(): string;

  /**
   * @method deleteQuery
   * @return string
   */
  public function deleteQuery(): string;

  /**
   * @method selectQuery
   * @return string
   */
  public function selectQuery(): string;

  /**
   * @method rowQuery
   * @return string
   */
  public function rowQuery(): string;
}
