<?php

namespace LaraCore\Framework\Db\FluidOrm\Exceptions;

use InvalidArgumentException;

class DataMapperInvalidArgumentException extends InvalidArgumentException
{
  public function __construct(string $message)
  {
    parent::__construct($message);
  }
}
