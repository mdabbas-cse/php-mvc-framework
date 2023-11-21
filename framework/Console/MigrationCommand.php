<?php

namespace LaraCore\Framework\Console;

use LaraCore\Framework\Console\Log;

class MigrationCommand
{
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

  public static function migrate()
  {
    $path = ROOT . DS . 'database' . DS . 'migrations';
    $migrationFiles = scandir($path);

    foreach ($migrationFiles as $migrationFile) {
      if ($migrationFile === '.' || $migrationFile === '..') {
        continue;
      }

      $migrationFilePath = $path . DS . $migrationFile;
      require_once $migrationFilePath;

      $migrationFileName = pathinfo($migrationFile, PATHINFO_FILENAME);
      $migrationClassName = "LaraCore\\Database\\Migrations\\{$migrationFileName}";

      $migration = new $migrationClassName();
      $migration->up();
    }
    Log::success("Migration run successfully");
  }

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

    $migrationContent = <<<EOL
<?php

namespace LaraCore\Database\Migrations;

use LaraCore\Framework\Db\Migrations\Blueprint;
use LaraCore\Framework\Db\Migrations\Migration;

class {$migrationFileName} extends Migration
{
 public function up()
  {
    \$this->create('{$migrationTable}', function (Blueprint \$table) {
      \$table->id();
     
      \$table->timestamps();
    });
  }

  public function down()
  {
    // \$this->drop('users');
  }
}
EOL;

    file_put_contents($migrationFilePath, $migrationContent);

    Log::info("Migration file created: $migrationFilePath");
  }

}