<?php

namespace MVC\App\Models;

use MVC\Framework\Model;

class RegisterModel extends Model
{
  protected $table = 'users';
  protected $fillable = ['firstname', 'lastname', 'email', 'password'];

  public function __construct()
  {
    parent::__construct();
  }

  public function register()
  {
    // $this->name = $_POST['name'];
    // $this->email = $_POST['email'];
    // $this->password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // $this->create();
  }
}
