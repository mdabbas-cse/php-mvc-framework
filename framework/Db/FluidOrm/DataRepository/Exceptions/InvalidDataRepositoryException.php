<?php

namespace LaraCore\Framework\Db\FluidOrm\DataRepository\Exceptions;

use InvalidArgumentException;
use Throwable;

class InvalidDataRepositoryException extends InvalidArgumentException
{
  public function __construct($message = "", $code = 0, Throwable $previous = null)
  {
    parent::__construct($message, $code, $previous);
  }
}