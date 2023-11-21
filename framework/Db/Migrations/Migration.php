<?php

namespace LaraCore\Framework\Db\Migrations;

use LaraCore\Framework\Application;
use LaraCore\Framework\Configuration;
use LaraCore\Framework\Db\Connection;
use PDO;

class Migration extends Application
{
  protected $pdo;
  public function __construct()
  {
    parent::__construct();
    $config = Configuration::get('database');
    $connection = Connection::make($config);
    $this->pdo = $connection;
  }

  /**
   * Run the "up" method on the migration.
   *
   * @return void
   */
  public function up()
  {
    // Implement the logic for creating or modifying the database table.
  }

  /**
   * Run the "down" method on the migration.
   *
   * @return void
   */
  public function down()
  {
    // Implement the logic for reverting the changes made in the "up" method.
  }

  /**
   * Create a new table with the given blueprint.
   *
   * @param string $tableName
   * @param \LaraCore\Framework\Db\Migrations\Blueprint $callback
   * @return void
   */
  public function create($tableName, $callback)
  {
    $columns = $callback->getColumns();
    $sql = "CREATE TABLE $tableName ($columns)";
    $this->execute($sql);
  }

  /**
   * Drop a table from the database.
   *
   * @param string $tableName
   * @return void
   */
  public function drop($tableName)
  {
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
    $columnDefinition = $callback->getCurrentColumnDefinition(''); // Get column definition from the Blueprint
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


  // /**
  //  * Drop a table
  //  */
  // public function drop($table)
  // {
  //   $sql = "DROP TABLE IF EXISTS $table";
  //   $this->pdo->exec($sql);
  // }

  /**
   * Build the columns
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
  public function applyMigrations()
  {
    $this->createMigrationsTable();
    $applyMigrations = $this->getApplyMigrationsTable();

    $migrationDir = scandir(ROOT . DS . 'database' . DS . 'migrations');

    $newMigrations = [];
    $files = scandir($migrationDir);
    $toApplyMigrations = array_diff($files, $applyMigrations);
    foreach ($toApplyMigrations as $migration) {
      if ($migration === '.' || $migration === '..') {
        continue;
      }
      require_once $migrationDir . DS . $migration;
      $className = pathinfo($migration, PATHINFO_FILENAME);
      $instance = new $className();
      // $this->log($migration);
      $this->log('success', "Applying migration $className");
      $instance->up();
      echo "Applied migration $className" . PHP_EOL;
      $this->log('success', "Applied migration $className");
      $newMigrations[] = $migration;
    }
    if (!empty($newMigrations)) {
      $this->saveMigrations($newMigrations);
    } else {
      $this->log('warning', 'All migrations are applied');
    }
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
   * Create Log massage
   * @param string $message
   * @return void
   */
  public function log($status, $message)
  {
    $msg = [
      'success' => "\033[32m" . $message . "\033[0m\n",
      'error' => "\033[31m" . $message . "\033[0m\n",
      'warning' => "\033[33m" . $message . "\033[0m\n",
      'info' => "\033[34m" . $message . "\033[0m\n",
    ];
    echo $msg[$status];
  }
}