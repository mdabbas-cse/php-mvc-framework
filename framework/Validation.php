<?php

namespace MVC\Framework;

use Error;
use MVC\Framework\Helpers\Errors;
use MVC\Framework\Helpers\Input;

class Validation
{
  private $request;
  private $data = [];
  private $errors = [];
  public const RULE_REQUIRED = 'required';
  public const RULE_EMAIL = 'email';
  public const RULE_MIN = 'min';
  public const RULE_MAX = 'max';
  public const RULE_MATCH = 'match';
  public const RULE_UNIQUE = 'unique';

  public function __construct($request, $data = [])
  {
    $this->request = $request;
    $this->data = $data;
  }

  public function checkValidation()
  {
    foreach ($this->data as $attribute => $rules) {
      $value = Input::sanitize($this->request[$attribute]);
      foreach ($rules as $rule) {
        $ruleName = $rule;
        if (!is_string($ruleName)) {
          $ruleName = $rule[0];
        }
        switch ($ruleName) {
          case self::RULE_REQUIRED:
            if (empty($value)) {
              $this->addError($attribute, self::RULE_REQUIRED);
            }
            break;
          case self::RULE_EMAIL:
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
              $this->addError($attribute, self::RULE_EMAIL);
            }
            break;
          case self::RULE_MIN:
            if (strlen($value) < $rule['min']) {
              $this->addError($attribute, self::RULE_MIN, $rule);
            }
            break;
          case self::RULE_MAX:
            if (strlen($value) > $rule['max']) {
              $this->addError($attribute, self::RULE_MAX, $rule);
            }
            break;
          case self::RULE_MATCH:
            if ($value !== $this->request[$rule['match']]) {
              $this->addError($attribute, self::RULE_MATCH, $rule);
            }
            break;
            // case self::RULE_UNIQUE:
            //   $unique = $this->db->select($this->table, $attribute, $value);
            //   if ($unique) {
            //     $this->addError($attribute, self::RULE_UNIQUE, ['field' => $attribute]);
            //   }
            //   break;
        }
      }
    }
    Errors::set($this->getErrors());
    return empty($this->errors);
  }

  public function addError(string $attribute, string $rule, $params = [])
  {
    $message = $this->errormessage()[$rule] ?? '';
    if (!$message) {
      throw new \Exception("There is no error message for $rule");
    }
    foreach ($params as $key => $value) {
      $message = str_replace("{{$key}}", $value, $message);
    }
    $this->errors[$attribute][] = $message;
  }
  public function errormessage()
  {
    return [
      'required' => 'This field is required',
      'email' => 'This field must be a valid email address',
      'min' => 'Min length of this field must be {min}',
      'max' => 'Max length of this field must be {max}',
      'match' => 'This field must be the same as {match}',
      'unique' => 'Record with this {field} already exists',
    ];
  }
  public function getErrors()
  {
    $errors = [];
    if (empty($this->errors)) {
      return false;
    }
    foreach ($this->errors as $key => $value) {
      $errors[$key] = $value[0];
    }
    return $errors;
  }

  public function isValidate()
  {
    return empty($this->errors);
  }
}
