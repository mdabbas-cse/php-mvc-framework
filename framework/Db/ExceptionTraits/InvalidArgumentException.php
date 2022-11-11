<?php

namespace Framework\Db\ExceptionTraits;

use Throwable;

trait InvalidArgumentException
{
  public function isArray(array $conditions, $message = null)
  {
    if (!is_array($conditions)) {
      throw new \InvalidArgumentException($message ?? 'The argument must be an array!');
    }
  }

  public function isString($string, $message = null)
  {
    if (!is_string($string)) {
      throw new \InvalidArgumentException($message ?? 'The argument must be a string!');
    }
  }

  public function isInt($int, $message = null)
  {
    if (!is_int($int)) {
      throw new \InvalidArgumentException($message ?? 'The argument must be an integer!');
    }
  }

  public function isBool($bool, $message = null)
  {
    if (!is_bool($bool)) {
      throw new \InvalidArgumentException($message ?? 'The argument must be a boolean!');
    }
  }

  public function isObject($object, $message = null)
  {
    if (!is_object($object)) {
      throw new \InvalidArgumentException($message ?? 'The argument must be an object!');
    }
  }

  public function isCallable($callable, $message = null)
  {
    if (!is_callable($callable)) {
      throw new \InvalidArgumentException($message ?? 'The argument must be callable!');
    }
  }

  public function isResource($resource, $message = null)
  {
    if (!is_resource($resource)) {
      throw new \InvalidArgumentException($message ?? 'The argument must be a resource!');
    }
  }

  public function isScalar($scalar, $message = null)
  {
    if (!is_scalar($scalar)) {
      throw new \InvalidArgumentException($message ?? 'The argument must be a scalar!');
    }
  }

  public function isNull($null, $message = null)
  {
    if (!is_null($null)) {
      throw new \InvalidArgumentException($message ?? 'The argument must be null!');
    }
  }

  public function isNumeric($numeric, $message = null)
  {
    if (!is_numeric($numeric)) {
      throw new \InvalidArgumentException($message ?? 'The argument must be numeric!');
    }
  }

  public function isIterable($iterable, $message = null)
  {
    if (!is_iterable($iterable)) {
      throw new \InvalidArgumentException($message ?? 'The argument must be iterable!');
    }
  }

  public function isCountable($countable, $message = null)
  {
    if (!is_countable($countable)) {
      throw new \InvalidArgumentException($message ?? 'The argument must be countable!');
    }
  }

  public function isEmpty($value, $message = null)
  {
    if (empty($value)) {
      throw new \InvalidArgumentException($message ?? 'The argument must not be empty!');
    }
  }
}
