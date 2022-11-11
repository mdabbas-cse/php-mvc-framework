<?php

namespace Framework\Db\FluidOrm\EntityManager\Exceptions;


use Framework\Db\FluidOrm\EntityManager\Exceptions\EntityManagerException;
use Throwable;

class CrudException extends EntityManagerException
{
  public function __construct($message = "", $code = 0, Throwable $previous = null)
  {
    parent::__construct($message, $code, $previous);
  }
}
