<?php

namespace Framework\Db\FluidOrm\EntityManager\Exceptions;

use Exception;
use Throwable;

class EntityManagerException extends Exception
{
  public function __construct($message = "", $code = 0, Throwable $previous = null)
  {
    parent::__construct($message, $code, $previous);
  }
}
