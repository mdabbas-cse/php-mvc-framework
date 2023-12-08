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
      echo "Usage: php laracore make:controller <controllerName>\n";
      exit(1);
    }

    // split the controller name by '/'
    $controllerNameArray = explode('/', $controllerNameArgv);
    $controllerNameArray = array_map(function ($item) {
      return ucfirst($item);
    }, $controllerNameArray);

    $controllerNameSpace = "LaraCore\\App\\Http\\Controllers";
    $controllersBasePath = 'app/Http/Controllers';

    if (count($controllerNameArray) > 1) {
      $controllerName = end($controllerNameArray);
      $pattern = '/Controller$/';
      if (!preg_match($pattern, $controllerName)) {
        $controllerName .= 'Controller';
      }
      $controllerNameSpace .= '\\' . str_replace('/', '\\', implode('/', array_slice($controllerNameArray, 0, -1)));
      $controllersBasePath .= '/' . implode('/', array_slice($controllerNameArray, 0, -1));
    } else {
      $controllerName = $controllerNameArray[0];
      $pattern = '/Controller$/';
      if (!preg_match($pattern, $controllerName)) {
        $controllerName .= 'Controller';
      }
    }
    unset($controllerNameArray[end($controllerNameArray)]);

    // Check if the controller class already exists
    $class = $controllerNameSpace . '\\' . $controllerName;
    if (class_exists($class)) {
      Log::error("Controller '$controllerName' already exists.");
      exit(1);
    }
    // controller file path
    $controllerPath = check_or_make_dir($controllersBasePath);
    $controllerPath .= DS . $controllerName . '.php';

    // get controller stub
    $controllerStubPath = base_path('framework/Stub/Controller.stub');
    $controllerStub = file_get_contents($controllerStubPath);

    Log::info("Controller file created...: $controllerPath");

    // replace stub
    $controllerContent = str_replace(
      ['{{Namespace}}', '{{ControllerName}}'],
      [$controllerNameSpace, $controllerName],
      $controllerStub
    );

    $createFile = file_put_contents($controllerPath, $controllerContent);
    if (!$createFile) {
      Log::error("Controller '$controllerName' not created.");
      exit(1);
    }
    Log::success("Controller '$controllerName' created successfully.");
  }

}