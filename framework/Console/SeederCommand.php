<?php

namespace LaraCore\Framework\Console;

use LaraCore\Database\Seeders\DatabaseSeeder;
use LaraCore\Framework\Console\Log;

class SeederCommand
{
  public static function run($argv)
  {
    if (!isset($argv[2])) {
      Log::warning("Usage: php laracore db:seed <seeder>");
      exit(1);
    }

    $seeder = $argv[2];
    $seeder = "LaraCore\\Database\\Seeders\\$seeder";

    if (!class_exists($seeder)) {
      Log::warning("Seeder $seeder does not exist");
      exit(1);
    }

    $seeder = new $seeder();
    $seeder->run();
  }

  public static function runDatabaseSeeder()
  {
    $databaseSeeder = new DatabaseSeeder();
    $databaseSeeder->run();

    Log::info("Database seeded successfully");
  }


}