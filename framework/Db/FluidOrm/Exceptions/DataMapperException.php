<?php

namespace LaraCore\Framework\Db\FluidOrm\Exceptions;

use Exception;

class DataMapperException extends Exception
{
  public function __construct(string $message = "", int $code = 0, \Throwable $previous = null)
  {
    parent::__construct($message, $code, $previous);
  }
}
