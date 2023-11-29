<?php

namespace LaraCore\Database\Migrations;

use LaraCore\Framework\Db\Migrations\Blueprint;
use LaraCore\Framework\Db\Migrations\Migration;

class Post_2023_11_22_072902 extends Migration
{
  public function up()
  {
    $this->create('posts', function (Blueprint $table) {
      $table->id();
      $table->string('title')->nullable()->default('this is a title');
      $table->string('slug');
      $table->text('content');
      $table->integer('user_id');
      $table->integer('category_id');
      // $table->integer('status')->default(0)->comment('0: draft, 1: published'); // it's not working
      $table->integer('status')->default(0);
      $table->timestamps();
    });
  }

  public function down()
  {
    $this->drop('posts');
  }
}