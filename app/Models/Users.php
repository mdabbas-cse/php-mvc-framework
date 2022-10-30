<?php

namespace MVC\App\Models;

use MVC\Framework\Db\DataModel;


class Users extends DataModel
{
  protected $table = 'users';
  protected $fillable = ['firstname', 'lastname', 'email', 'password'];

  public function tableName(): string
  {
    return $this->table;
  }

  public function attributes(): array
  {
    return $this->fillable;
  }

  // public function register()
  // {
  //   return $this->save();
  // }
}
