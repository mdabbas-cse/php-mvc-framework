<?php

namespace LaraCore\Database\Seeders;

use LaraCore\Framework\Db\Seeders\Seeder;

class DatabaseSeeder extends Seeder
{
  public function run()
  {
    $this->callMany([
      UserSeeder::class,
      // SubmissionSeeder::class
    ]);
  }
}