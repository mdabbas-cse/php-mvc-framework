<?php

namespace Lora\Core\Framework\Db\FluidOrm\QueryBuilder;

use Lora\Core\Framework\Db\FluidOrm\QueryBuilder\Exceptions\QueryBuilderInvalidArgumentException;

class QueryBuilder implements QueryBuilderInterface
{
  protected array $key;

  protected const SQL_DEFAULT = [
    'conditions' => [],
    'selectors' => [],
    'replace' => false,
    'distinct' => false,
    'from' => [],
    'where' => null,
    'and' => [],
    'or' => [],
    'orderby' => [],
    'fields' => [],
    'primary_key' => '',
    'table' => '',
    'type' => '',
    'raw' => '',

    'table_join' => '',
    'join_key' => '',
    'join' => []
  ];
  /**
   * @var string
   */

  /**
   * Main constructor class
   * 
   * @return void
   */
  public function __construct()
  {
    //
  }

  /**
   * @method buildQuery
   * @param array $args
   */
  public function buildQuery(array $args): void
  {
    if (count($args) < 0) {
      throw new QueryBuilderInvalidArgumentException('Invalid arguments. The arguments must be an array');
    }
  }
}
