<?php

namespace MVC\Framework;

use MVC\Framework\Db\Connection;
use MVC\Framework\Db\QueryBuilder;

abstract class Model
{
  protected $db;
  public $errors = [];
  public const RULE_REQUIRED = 'required';
  public const RULE_EMAIL = 'email';
  public const RULE_MIN = 'min';
  public const RULE_MAX = 'max';
  public const RULE_MATCH = 'match';
  public const RULE_UNIQUE = 'unique';


  public function __construct()
  {
    $this->db = new QueryBuilder(Connection::make(App::get('config')['database']));
  }

  abstract public function role(): array;

  public function loadData($data)
  {
    foreach ($data as $key => $value) {
      if (property_exists($this, $key)) {
        $this->{$key} = $value;
      }
    }
  }

  // for validate
  public function validate()
  {
    foreach ($this->role() as $attribute => $rules) {
      $value = $this->{$attribute};
      foreach ($rules as $rule) {
        $ruleName = $rule;
        if (!is_string($ruleName)) {
          $ruleName = $rule[0];
        }
        if ($ruleName === self::RULE_REQUIRED && !$value) {
          $this->addError($attribute, self::RULE_REQUIRED);
        }
        if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
          $this->addError($attribute, self::RULE_EMAIL);
        }
        if ($ruleName === self::RULE_MIN && strlen($value) < $rule['min']) {
          $this->addError($attribute, self::RULE_MIN, $rule);
        }
        if ($ruleName === self::RULE_MAX && strlen($value) > $rule['max']) {
          $this->addError($attribute, self::RULE_MAX, $rule);
        }
        if ($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']}) {
          $this->addError($attribute, self::RULE_MATCH, $rule);
        }
        if ($ruleName === self::RULE_UNIQUE) {
          // $unique = $this->db->selectOne($this->table, [$attribute => $value]);
          // if ($unique) {
          //   $this->addError($attribute, self::RULE_UNIQUE, ['field' => $attribute]);
          // }
        }
      }
    }
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
      self::RULE_REQUIRED => 'This field is required',
      self::RULE_EMAIL => 'This field must be a valid email address',
      self::RULE_MIN => 'Min length of this field must be {min}',
      self::RULE_MAX => 'Max length of this field must be {max}',
      self::RULE_MATCH => 'This field must be the same as {match}',
      self::RULE_UNIQUE => 'Record with this {field} already exists',
    ];
  }
}
