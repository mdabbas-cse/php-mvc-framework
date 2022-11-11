<?php

namespace Lora\Core\Framework\Db\ExceptionTraits;

trait InvalidArgumentException
{
  /**
   * @method array isArray() for checking if the argument is an array
   * @param array $conditions
   * @param mixed $message
   * @throws \InvalidArgumentException 
   * @return void
   */
  public function isArray(array $array, $message = null)
  {
    if (!is_array($array)) {
      throw new \InvalidArgumentException($message ?? 'The argument must be an array!');
    }
  }

  /**
   * @method empty isEmpty() for checking if the argument is empty
   * @param mixed $argument
   * @param mixed $message
   * @throws \InvalidArgumentException 
   * @return void
   */
  public function isEmpty($value, $message = null)
  {
    if (empty($value)) {
      throw new \InvalidArgumentException($message ?? 'The argument must not be empty!');
    }
  }

  /**
   * @method string isString() for checking if the argument is a string
   * @param mixed $string
   * @param mixed $message
   * @throws \InvalidArgumentException 
   * @return void
   */
  public function isString($string, $message = null)
  {
    if (!is_string($string)) {
      throw new \InvalidArgumentException($message ?? 'The argument must be a string!');
    }
  }

  /**
   * @method int isInt() for checking if the argument is an integer
   * @param mixed $int
   * @param mixed $message
   * @throws \InvalidArgumentException 
   * @return void
   */
  public function isInt($int, $message = null)
  {
    if (!is_int($int)) {
      throw new \InvalidArgumentException($message ?? 'The argument must be an integer!');
    }
  }

  /**
   * @method float isFloat() for checking if the argument is a float
   * @param mixed $float
   * @param mixed $message
   * @throws \InvalidArgumentException 
   * @return void
   */
  public function isFloat($float, $message = null)
  {
    if (!is_float($float)) {
      throw new \InvalidArgumentException($message ?? 'The argument must be a float!');
    }
  }

  /**
   * @method bool isBool() for checking if the argument is a boolean
   * @param mixed $bool
   * @param mixed $message
   * @throws \InvalidArgumentException 
   * @return void
   */
  public function isBool($bool, $message = null)
  {
    if (!is_bool($bool)) {
      throw new \InvalidArgumentException($message ?? 'The argument must be a boolean!');
    }
  }

  /**
   * @method object isObject() for checking if the argument is an object
   * @param mixed $object
   * @param mixed $message
   * @throws \InvalidArgumentException 
   * @return void
   */
  public function isObject($object, $message = null)
  {
    if (!is_object($object)) {
      throw new \InvalidArgumentException($message ?? 'The argument must be an object!');
    }
  }

  /**
   * @method resource isResource() for checking if the argument is a resource
   * @param mixed $resource
   * @param mixed $message
   * @throws \InvalidArgumentException 
   * @return void
   */
  public function isResource($resource, $message = null)
  {
    if (!is_resource($resource)) {
      throw new \InvalidArgumentException($message ?? 'The argument must be a resource!');
    }
  }

  /**
   * @method Null isNull() for checking if the argument is null
   * @param mixed $null
   * @param mixed $message
   * @throws \InvalidArgumentException 
   * @return void
   */
  public function isNull($null, $message = null)
  {
    if (!is_null($null)) {
      throw new \InvalidArgumentException($message ?? 'The argument must be null!');
    }
  }

  /**
   * @method callable isCallable() for checking if the argument is callable
   * @param mixed $callable
   * @param mixed $message
   * @throws \InvalidArgumentException 
   * @return void
   */
  public function isCallable($callable, $message = null)
  {
    if (!is_callable($callable)) {
      throw new \InvalidArgumentException($message ?? 'The argument must be callable!');
    }
  }

  /**
   * @method scalar isScalar() for checking if the argument is a scalar
   * @param mixed $scalar
   * @param mixed $message
   * @throws \InvalidArgumentException 
   * @return void
   */
  public function isScalar($scalar, $message = null)
  {
    if (!is_scalar($scalar)) {
      throw new \InvalidArgumentException($message ?? 'The argument must be a scalar!');
    }
  }

  /**
   * @method numeric isNumeric() for checking if the argument is numeric
   * @param mixed $numeric
   * @param mixed $message
   * @throws \InvalidArgumentException 
   * @return void
   */
  public function isNumeric($numeric, $message = null)
  {
    if (!is_numeric($numeric)) {
      throw new \InvalidArgumentException($message ?? 'The argument must be numeric!');
    }
  }

  /**
   * @method countable isCountable() for checking if the argument is countable
   * @param mixed $countable
   * @param mixed $message
   * @throws \InvalidArgumentException 
   * @return void
   */
  public function isCountable($countable, $message = null)
  {
    if (!is_countable($countable)) {
      throw new \InvalidArgumentException($message ?? 'The argument must be countable!');
    }
  }

  /**
   * @method iterable isIterable() for checking if the argument is iterable
   * @param mixed $iterable
   * @param mixed $message
   * @throws \InvalidArgumentException 
   * @return void
   */
  public function isIterable($iterable, $message = null)
  {
    if (!is_iterable($iterable)) {
      throw new \InvalidArgumentException($message ?? 'The argument must be iterable!');
    }
  }
}
