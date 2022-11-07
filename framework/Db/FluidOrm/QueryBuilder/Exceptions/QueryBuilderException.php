<?php


namespace Lora\Core\Framework\Db\FluidOrm\QueryBuilder\Exceptions;

use Exception;

class QueryBuilderException extends Exception
{
  public function __construct(string $message)
  {
    parent::__construct($message);
  }
}
