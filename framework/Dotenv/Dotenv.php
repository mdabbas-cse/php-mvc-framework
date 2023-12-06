<?php

namespace LaraCore\Framework\Dotenv;

class Dotenv {
  /**
   * The path to the .env file.
   *
   * @var string
   */
  protected $path;

  /**
   * The environment file version.
   *
   * @var string
   */
  protected $version = '1.0';

  /**
   * The full path of the .env file.
   * @var  string
   */
  private $filePath;

  protected $env;
  public function __construct($path, $env = null) {

    $this->path = rtrim($path, DIRECTORY_SEPARATOR);
    $this->env = is_null($env) ? '.env' : $env;
    $this->filePath = $this->path.DIRECTORY_SEPARATOR.$this->env;
  }

  public static function createImmutable($path, $env = null) {
    return new static($path, $env);
  }
  public function load() {
    $path = $this->filePath;

    if(file_exists($path)) {
      $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      foreach($lines as $line) {
        if(strpos(trim($line), '#') === 0)
          continue;
        list($name, $value) = explode('=', $line, 2);

        $name = trim($name);
        $value = trim($value);
        if(!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
          putenv(sprintf('%s=%s', $name, $value));
          $_ENV[$name] = $value;
          $_SERVER[$name] = $value;
        }
      }
    } else {
      throw new \Exception(sprintf('%s does not exist', $path));
    }
  }

  public function get($key, $default = null) {
    $value = getenv($key);
    if($value === false) {
      return $default;
    }
    switch(strtolower($value)) {
      case $value === 'true':
      case $value === '(true)':
        return true;
      case $value === 'false':
      case $value === '(false)':
        return false;
      case $value === 'empty':
      case $value === '(empty)':
        return '';
      case $value === 'null':
      case $value === '(null)':
        return;
    }
    if(strlen($value) > 1 && strpos($value, '"') === 0 && strrpos($value, '"') === strlen($value) - 1) {
      return substr($value, 1, -1);
    }
    return $value;
  }


  public function set($key, $value = null) {
    if(is_array($key)) {
      foreach($key as $k => $v) {
        $this->set($k, $v);
      }
    } else {
      putenv(sprintf('%s=%s', $key, $value));
      $_ENV[$key] = $value;
      $_SERVER[$key] = $value;
    }
  }
}