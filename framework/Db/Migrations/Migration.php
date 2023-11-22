<?php

namespace LaraCore\Framework\Db\Migrations;

use LaraCore\Framework\Application;
use LaraCore\Framework\Configuration;
use LaraCore\Framework\Console\Log;
use LaraCore\Framework\Db\Connection;
use PDO;

class Migration extends Application
{
  /**
   * The PDO instance.
   *
   * @var \PDO
   */
  protected $pdo;
  protected $connection;
  protected $config;

  const CREATE_TABLE = 'CREATE TABLE';

  const MIGRATIONS_DIR = ROOT . DS . 'database' . DS . 'migrations';

  const MIGRATIONS_PATH_NAMESPACE = 'LaraCore\\Database\\Migrations\\';

  public function __construct()
  {
    parent::__construct();
    $config = Configuration::get('database');
    $connection = Connection::make($config);
    $this->pdo = $connection;
  }

  /**
   * Create a new table with the given blueprint.
   *
   * @param string $tableName
   * @param \LaraCore\Framework\Db\Migrations\Blueprint $callback
   * @return void
   */
  public function create($tableName, callable $callback)
  {
    $bluePrintInstance = new Blueprint();
    $callback($bluePrintInstance);
    $columns = $bluePrintInstance->getColumns();
    $sql = $this->createQuery($tableName, $columns);
    $this->execute($sql);
  }

  /**
   * Create the SQL query for creating a table.
   *
   * @param string $tableName
   * @param array $columns
   * @return string
   */
  private function createQuery($tableName, $columns)
  {
    $sql = self::CREATE_TABLE . " $tableName (";
    $sql .= implode(',', $columns);
    $sql = rtrim($sql, ',');
    $sql .= ')';
    return $sql;
  }

  /**
   * Drop a table from the database.
   *
   * @param string $tableName
   * @return void
   */
  public function drop($tableName)
  {
    print_r($tableName);
    $sql = "DROP TABLE IF EXISTS $tableName";
    $this->execute($sql);
  }

  /**
   * Rename a table in the database.
   *
   * @param string $oldName
   * @param string $newName
   * @return void
   */
  public function rename($oldName, $newName): void
  {
    $sql = "ALTER TABLE $oldName RENAME TO $newName";
    $this->execute($sql);
  }

  /**
   * Add a new column to the given table.
   *
   * @param string $tableName
   * @param string $columnName
   * @param \LaraCore\Framework\Db\Migrations\Blueprint $callback
   * @return void
   */
  public function addColumn($tableName, $columnName, $callback)
  {
    $columnDefinition = $callback->getCurrentColumnDefinition(''); // Get column definition from the Blueprint
    $sql = "ALTER TABLE $tableName ADD COLUMN $columnDefinition";
    $this->execute($sql);
  }

  /**
   * Drop a column from the given table.
   *
   * @param string $tableName
   * @param string $columnName
   * @return void
   */
  public function dropColumn($tableName, $columnName)
  {
    $sql = "ALTER TABLE $tableName DROP COLUMN $columnName";
    $this->execute($sql);
  }

  /**
   * Modify a column on the given table.
   *
   * @param string $tableName
   * @param string $columnName
   * @param \LaraCore\Framework\Db\Migrations\Blueprint $callback
   * @return void
   */
  public function modifyColumn($tableName, $columnName, $callback)
  {
    $columnDefinition = $callback->getCurrentColumnDefinition($columnName); // Get column definition from the Blueprint
    $sql = "ALTER TABLE $tableName MODIFY COLUMN $columnDefinition";
    $this->execute($sql);
  }

  /**
   * Add a new index to the given table.
   *
   * @param string $tableName
   * @param string|array $columns
   * @param string|null $indexName
   * @return void
   */
  public function addIndex($tableName, $columns, $indexName = null)
  {
    $columns = is_array($columns) ? implode(', ', $columns) : $columns;
    $indexName = $indexName ? "INDEX $indexName" : '';
    $sql = "CREATE $indexName ON $tableName ($columns)";
    $this->execute($sql);
  }

  /**
   * Drop an index from the given table.
   *
   * @param string $tableName
   * @param string $indexName
   * @return void
   */
  public function dropIndex($tableName, $indexName)
  {
    $sql = "DROP INDEX $indexName ON $tableName";
    $this->execute($sql);
  }

  /**
   * Execute a raw SQL query.
   *
   * @param string $sql
   * @return void
   */
  protected function execute($sql)
  {
    // Execute the SQL query.
    $this->pdo->exec($sql);
  }

  /**
   * Build the columns
   * 
   * @param callable $callback
   * @return string
   */
  private function buildColumns($callback)
  {
    $sql = call_user_func($callback, new Blueprint);
    return $sql;
  }

  /**
   * Create migration table
   * @return void
   */
  protected function createMigrationsTable()
  {
    $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )  ENGINE=INNODB;");
  }
  /**
   * Get all the migrations
   * @return array
   */
  public function getApplyMigrationsTable()
  {
    $statement = $this->pdo->prepare("SELECT migration FROM migrations");
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_COLUMN);
  }

  /**
   * Apply all migrations
   * @return void
   */
  public function applyMigrations($argv)
  {
    $this->createMigrationsTable();
    $applyMigrations = $this->getApplyMigrationsTable();

    // check arguments for specific migration
    if (isset($argv[2])) {
      if (in_array($argv[2], $applyMigrations)) {
        Log::warning("Migration {$argv[2]} already applied");
        return;
      }
      $this->specificMigration($argv[2], $newMigrations);
    } else {
      $newMigrations = [];
      $files = scandir(self::MIGRATIONS_DIR);
      $toApplyMigrations = array_diff($files, $applyMigrations);

      foreach ($toApplyMigrations as $migration) {
        if ($migration === '.' || $migration === '..') {
          continue;
        }
        $this->specificMigration($migration, $newMigrations);
      }
    }
    if (!empty($newMigrations)) {
      $this->saveMigrations($newMigrations);
      Log::success("Applied migration successfully");
    } else {
      Log::warning('All migrations are applied');
    }
  }

  /**
   * Define for specific migration
   * 
   * @param string $migration
   * @param array $newMigrations
   * @return void
   */
  private function specificMigration($migration, &$newMigrations)
  {
    require_once self::MIGRATIONS_DIR . DS . $migration;
    $migrationFileName = pathinfo($migration, PATHINFO_FILENAME);
    $migrationClassName = self::MIGRATIONS_PATH_NAMESPACE . $migrationFileName;
    $instance = new $migrationClassName();
    Log::warning("Applying migration $migrationClassName");
    $instance->up();
    Log::info("Applied migration $migrationClassName");
    $newMigrations[] = $migration;
  }



  /**
   * Save migrations to database
   * @param array $migrations
   * @return void
   */
  public function saveMigrations(array $migrations)
  {
    $str = implode(
      ",",
      array_map(function ($m) {
        return "('$m')";
      }, $migrations)
    );
    $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $str");
    $statement->execute();
  }

  /**
   * Delete migrations from database
   * 
   * @param array $migrations
   * @return void
   */
  public function deleteMigrations(array $migrations)
  {
    $str = implode(
      ",",
      array_map(function ($m) {
        return "'$m'";
      }, $migrations)
    );
    $statement = $this->pdo->prepare("DELETE FROM migrations WHERE migration IN ($str)");
    $statement->execute();
  }


  /**
   * Rollback all migrations
   * @return void
   */
  public function rollbackMigrations()
  {
    $applyMigrations = $this->getApplyMigrationsTable();

    $newMigrations = [];
    $files = scandir(self::MIGRATIONS_DIR);

    foreach ($files as $migration) {
      if (
        $migration === '.'
        || $migration === '..'
        || !in_array($migration, $applyMigrations)
      ) {
        continue;
      }

      $migrationFileName = self::MIGRATIONS_DIR . DS . $migration;
      require_once $migrationFileName;
      $migrationFileName = pathinfo($migration, PATHINFO_FILENAME);
      $migrationClassName = self::MIGRATIONS_PATH_NAMESPACE . $migrationFileName;
      $instance = new $migrationClassName();
      Log::warning("Applying migration rollback $migrationClassName");
      $instance->down();
      Log::info("Applied migration rollback $migrationClassName");
      $newMigrations[] = $migration;
    }
    if (!empty($newMigrations)) {
      $this->deleteMigrations($newMigrations);
      Log::success("Applied migration rollback successfully");
    } else {
      Log::warning('All migrations are applied');
    }
  }
}