<?php

namespace LaraCore\Framework\Console;

use LaraCore\Framework\Console\Log;

class ControllerCommand
{
  /**
   * Summary of make controller
   * @param mixed $argv
   * @return void
   */
  public static function make($argv)
  {
    // Extract migration name from the command-line arguments
    $controllerNameArgv = $argv[2] ?? null;

    if (!$controllerNameArgv) {
      echo "Usage: php laracore make:migration <controllerName>\n";
      exit(1);
    }

    // split the controller name by '/'
    $controllerNameArray = explode('/', $controllerNameArgv);
    $controllerName = end($controllerNameArray);
    $pattern = '/Controller$/';
    if (!preg_match($pattern, $controllerName)) {
      print_r('not match');
      $controllerName .= 'Controller';
    }

    // create file path
    $filePath = null;
    $replaceNamespace = null;
    if (count($controllerNameArray) > 1) {
      $newArray = array_slice($controllerNameArray, 0, -1);
      $replaceNamespace = '\\' . str_replace('/', '\\', $newArray);
      print_r(['$replaceNamespace' => $replaceNamespace]);
      $filePath = DS . implode(DS, $controllerNameArray);
    }

    $controllerNameSpace = "LaraCore\\App\\Http\\Controllers{$replaceNamespace}";

    // Check if the controller class already exists
    if (class_exists($controllerNameSpace)) {
      Log::error("Controller '$controllerName' already exists.");
      exit(1);
    }
    $path = ROOT . DS . 'app' . DS . 'Http' . DS . 'Controllers' . $filePath;

    // Check if the Migrations directory exists, and create it if not
    if (!is_dir($path)) {
      mkdir($path);
    }

    $path = $path . DS . $controllerName . '.php';

    $controllerStub = file_get_contents(ROOT . DS . 'framework' . DS . 'Stub' . DS . 'Controller.stub');
    print_r([
      $path,
      $replaceNamespace,
    ]);
    exit;
    Log::info("Controller file created: $path");

    $controllerContent = str_replace(
      ['{{Namespace}}', '{{ControllerName}}'],
      [$controllerNameSpace, $controllerName],
      $controllerStub
    );

    $createFile = file_put_contents($path, $controllerContent);
    if (!$createFile) {
      Log::error("Controller $controllerName not created.");
      exit(1);
    }
    Log::success("Controller $controllerName created successfully.");
  }

  /**
   * Summary of createMigrationFile
   * @param mixed $migrationName
   * @return void
   */
  private static function createMigrationFile($migrationName)
  {

  }

}