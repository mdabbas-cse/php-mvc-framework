<?php

namespace MVC\Framework\Db\Migrations;

use MVC\Framework\Application;
use MVC\Framework\Configuration;
use MVC\Framework\Db\Connection;
use PDO;

class Migration extends Application
{
  protected $pdo;
  public function __construct()
  {
    parent::__construct();
    $config = Configuration::get('database');
    $connection =  Connection::make($config);
    $this->pdo = $connection;
  }
  /**
   * Create a new table
   */
  public function create($table, $callback)
  {
    var_dump($table, $callback);
    // $sql = "CREATE TABLE IF NOT EXISTS $table (";
    // $sql .= $this->buildColumns($callback);
    // $sql .= ")";
    // var_dump($sql);
    // $this->pdo->exec($sql);
  }


  /**
   * Drop a table
   */
  public function drop($table)
  {
    $sql = "DROP TABLE IF EXISTS $table";
    $this->pdo->exec($sql);
  }

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
    $applyMigrations =  $this->getApplyMigrationsTable();

    $newMigrations = [];
    $files = scandir(ROOT . DS . 'database' . DS . 'migrations');
    $toApplyMigrations = array_diff($files, $applyMigrations);
    foreach ($toApplyMigrations as $migration) {
      if ($migration === '.' || $migration === '..') {
        continue;
      }
      require_once ROOT . DS . 'database' . DS . 'migrations' . DS . $migration;
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
