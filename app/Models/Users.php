<?php

namespace Lora\Core\App\Models;

use Lora\Core\Framework\Db\DataModel;
use Lora\Core\Framework\Helpers\Hash;
use Lora\Core\Framework\Session;

class Users extends DataModel
{
  protected $table = 'users';
  protected $fillable = ['firstname', 'lastname', 'email', 'password', 'status'];

  public function tableName(): string
  {
    return $this->table;
  }

  public function attributes(): array
  {
    return $this->fillable;
  }

  public function save()
  {
    $this->password = Hash::make($this->password);
    $this->status = 1;
    return parent::save();
  }

  /**
   * login user
   */
  public function login()
  {
    $user = $this->findOne(['email' => $this->email]);
    if ($user) {
      if (Hash::verify($this->password, $user->password)) {
        Session::set('user', $user);
      }
    }
  }
}
