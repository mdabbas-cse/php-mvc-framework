<?php

namespace LaraCore\Framework\Console;

use LaraCore\Framework\Console\Log;

class ModelCommand
{
  /**
   * Summary of make controller
   * @param mixed $argv
   * @return void
   */
  public static function make($argv)
  {
    // Extract migration name from the command-line arguments
    $modelNameArgv = $argv[2] ?? null;

    if (!$modelNameArgv) {
      echo "Usage: php laracore make:model <modelName>\n";
      exit(1);
    }

    // split the model name by '/'
    $modelNameArray = explode('/', $modelNameArgv);
    $modelNameArray = array_map(function ($item) {
      return ucfirst($item);
    }, $modelNameArray);

    $modelNameSpace = "LaraCore\\App\\Models";
    $modelsBasePath = 'app/Models';

    if (count($modelNameArray) > 1) {
      $modelName = end($modelNameArray);
      $modelNameSpace .= '\\' . str_replace('/', '\\', implode('/', array_slice($modelNameArray, 0, -1)));
      $modelsBasePath .= '/' . implode('/', array_slice($modelNameArray, 0, -1));
    } else {
      $modelName = $modelNameArray[0];
    }
    unset($modelNameArray[end($modelNameArray)]);

    // Check if the controller class already exists
    $class = $modelNameSpace . '\\' . $modelName;
    if (class_exists($class)) {
      Log::error("Model '$modelName' already exists.");
      exit(1);
    }
    // controller file path
    $modelPath = check_or_make_dir($modelsBasePath);
    $modelPath .= DS . $modelName . '.php';

    // get controller stub
    $modelStubPath = base_path('framework/Stub/Model.stub');
    $modelStub = file_get_contents($modelStubPath);

    Log::info("Model file created...: $modelPath");

    // replace stub
    $modelContent = str_replace(
      ['{{Namespace}}', '{{ModelName}}'],
      [$modelNameSpace, $modelName],
      $modelStub
    );

    $createFile = file_put_contents($modelPath, $modelContent);
    if (!$createFile) {
      Log::error("Model '$modelName' not created.");
      exit(1);
    }
    Log::success("Model '$modelName' created successfully.");
  }

}