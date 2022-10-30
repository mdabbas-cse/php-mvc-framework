<?php

class table_002
{
  public function up()
  {
    echo "Applying migration " . __CLASS__ . PHP_EOL;
    // $this->create('users', function ($table) {
    //   $table->increments('id');
    //   $table->string('name');
    //   $table->string('email');
    //   $table->string('password');
    //   $table->timestamps();
    // });
  }

  public function down()
  {
    // $this->drop('users');
  }
}
