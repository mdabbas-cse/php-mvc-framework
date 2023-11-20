<?php

use Lora\Core\Framework\Db\Migrations\Blueprint;
use Lora\Core\Framework\Db\Migrations\Migration;

class table_001 extends Migration
{
  public function up()
  {
    $this->create('users', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name');
      $table->string('email');
      $table->string('password');
      $table->timestamps();
    });
  }

  public function down()
  {
    // $this->drop('users');
  }
}