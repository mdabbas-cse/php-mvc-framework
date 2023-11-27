<?php

namespace LaraCore\Framework\Console;

use LaraCore\Framework\Console\Log;

class ControllerCommand
{
  /**
   * The singleton instance of this class.
   *
   * @var \LaraCore\Framework\Console\ControllerCommand
   */
  protected static $instance;


  /**
   * Define self instance for singleton pattern
   * 
   */
  public static function getInstance()
  {
    if (!self::$instance) {
      self::$instance = new ControllerCommand();
    }
    return self::$instance;
  }

  /**
   * Summary of makeMigration
   * @param mixed $argv
   * @return void
   */
  public static function makeController($argv)
  {
    // Extract migration name from the command-line arguments
    $controllerName = $argv[2] ?? null;

    if (!$controllerName) {
      echo "Usage: php laracore make:migration <controllerName>\n";
      exit(1);
    }

    $controllerClassName = "LaraCore\\App\\Http\\Controllers\\{$controllerName}";

    // Check if the controller class already exists
    if (class_exists($controllerClassName)) {
      Log::error("Controller '$controllerName' already exists.");
      exit(1);
    }
    self::createMigrationFile($controllerName);

    Log::success("Migration $controllerName created successfully.");
  }

  /**
   * Summary of applyMigrations
   * @return void
   */
  public static function migrate($argv)
  {
    // get instance of MigrationCommand
    $migrationCommand = self::getInstance();
    $migrationCommand->run($argv);
  }

  /**
   * Summary of createMigrationFile
   * @param mixed $migrationName
   * @return void
   */
  private static function createMigrationFile($migrationName)
  {
    $path = ROOT . DS . 'database' . DS . 'migrations';
    $migrationFileName = $migrationName . '_' . date('Y_m_d_His');
    $migrationFilePath = $path . DS . $migrationFileName . '.php';
    $migrationTable = strtolower($migrationName) . 's';

    // Check if the Migrations directory exists, and create it if not
    if (!is_dir($path)) {
      mkdir($path);
    }
    $migrationStub = file_get_contents(ROOT . DS . 'framework' . DS . 'Stub' . DS . 'Migration.stub');

    $migrationContent = str_replace(
      ['{{migtation_name}}', '{{table_name}}'],
      [$migrationFileName, $migrationTable],
      $migrationStub
    );

    file_put_contents($migrationFilePath, $migrationContent);

    Log::info("Migration file created: $migrationFilePath");
  }

  /**
   * Summary of run
   * @return void
   */
  public function run($argv)
  {
    $this->applyMigrations($argv);
  }

  /**
   * Summary of Migration rollback
   * @return void
   */
  public static function rollback()
  {
    // get instance of MigrationCommand
    $migrationCommand = self::getInstance();
    $migrationCommand->rollbackMigrations();
  }
}