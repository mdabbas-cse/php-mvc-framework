<?php

namespace LaraCore\Framework\Db\FluidOrm\DataRepository\Exceptions;

use Exception;
use Throwable;

class DataRepositoryException extends Exception
{
  public function __construct($message = "", $code = 0, Throwable $previous = null)
  {
    parent::__construct($message, $code, $previous);
  }
}