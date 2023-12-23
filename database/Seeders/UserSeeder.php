<?php

namespace LaraCore\Database\Seeders;

use LaraCore\Database\Factories\UserFactory;
use LaraCore\Framework\Seeders\Seeder;

class UserSeeder extends Seeder
{
  public function run()
  {
    // call the UserFactory to create a quiz
    UserFactory::new()->create();
  }
}