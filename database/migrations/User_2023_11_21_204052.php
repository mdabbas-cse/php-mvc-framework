<?php

namespace LaraCore\Database\Migrations;

use LaraCore\Framework\Db\Migrations\Blueprint;
use LaraCore\Framework\Db\Migrations\Migration;

class User_2023_11_21_204052 extends Migration
{
  public function up()
  {
    $this->create('users', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('email');
      $table->string('password');
      $table->timestamps();
    });
  }

  public function down()
  {
    $this->drop('users');
  }
}