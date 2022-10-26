<?php

namespace MVC\App\Models;

use MVC\Framework\Model;

class RegisterModel extends Model
{
  protected $table = 'users';
  protected $fillable = ['name', 'email', 'password'];

  // public $name;
  public $email;
  public $password;

  public function __construct()
  {
    parent::__construct();
  }

  public function role(): array
  {
    return [
      'name' => self::RULE_REQUIRED,
      'email' => [self::RULE_REQUIRED, self::RULE_EMAIL],
      'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8], [self::RULE_MAX, 'max' => 24]],
      'confirmPassword' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'password']],
    ];
  }

  public function register()
  {
    // $this->name = $_POST['name'];
    // $this->email = $_POST['email'];
    // $this->password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // $this->create();
  }
}
