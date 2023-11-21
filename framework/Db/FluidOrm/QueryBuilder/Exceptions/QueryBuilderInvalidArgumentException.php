<?php

namespace LaraCore\Framework\Db\FluidOrm\QueryBuilder\Exceptions;

use InvalidArgumentException;

class QueryBuilderInvalidArgumentException extends InvalidArgumentException
{
  public function __construct(string $message)
  {
    parent::__construct($message);
  }
}
