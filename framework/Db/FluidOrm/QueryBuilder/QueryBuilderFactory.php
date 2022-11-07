<?php

namespace Lora\Core\Framework\Db\FluidOrm\QueryBuilder;

use Lora\Core\Framework\Db\FluidOrm\QueryBuilder\Exceptions\QueryBuilderException;
use Lora\Core\Framework\Db\FluidOrm\QueryBuilder\QueryBuilderInterface;


class QueryBuilderFactory
{
  public function __construct()
  {
  }

  public function create(string $queryBuilderString): QueryBuilderInterface
  {
    if (!$queryBuilderString instanceof QueryBuilderInterface) {
      throw new QueryBuilderException($queryBuilderString . ' is not a valid QueryBuilder');
    }
    return $queryBuilderString;
  }
}
