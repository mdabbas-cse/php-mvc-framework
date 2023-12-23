<?php

namespace LaraCore\Framework\Seeders;

abstract class Seeder
{
  abstract public function run();

  public function call($seeder)
  {
    $seeder = new $seeder();
    $seeder->run();
  }

  public function callMany($seeders)
  {
    foreach ($seeders as $seeder) {
      $this->call($seeder);
    }
  }
  public function callOne($seeder)
  {
    $this->call($seeder);
  }
  public function callManyThrough($seeder)
  {
    $this->call($seeder);
  }
}