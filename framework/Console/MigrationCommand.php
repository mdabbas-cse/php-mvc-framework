<?php

namespace LaraCore\Framework\Console;

use LaraCore\Framework\Console\Log;
use LaraCore\Framework\Db\Migrations\Migration;

class MigrationCommand extends Migration
{
  /**
   * The singleton instance of this class.
   *
   * @var \LaraCore\Framework\Console\MigrationCommand
   */
  protected static $instance;

  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Define self instance for singleton pattern
   * 
   */
  public static function getInstance()
  {
    if (!self::$instance) {
      self::$instance = new MigrationCommand();
    }
    return self::$instance;
  }

  /**
   * Summary of makeMigration
   * @param mixed $argv
   * @return void
   */
  public static function makeMigration($argv)
  {
    // Extract migration name from the command-line arguments
    $migrationName = $argv[2] ?? null;

    if (!$migrationName) {
      echo "Usage: php laracore make:migration <MigrationName>\n";
      exit(1);
    }

    $migrationClassName = "LaraCore\\Database\\{$migrationName}";

    // Check if the migration class already exists
    if (class_exists($migrationClassName)) {
      Log::error("Migration $migrationName already exists.");
      exit(1);
    }
    self::createMigrationFile($migrationName);

    Log::success("Migration $migrationName created successfully.");
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
      ['{{migration_name}}', '{{table_name}}'],
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