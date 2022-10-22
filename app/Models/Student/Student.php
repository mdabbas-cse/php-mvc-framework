<?php


namespace MVC\App\Models\Student;

use MVC\App\Models\User\User;

class Student
{

  public static function name()
  {
    User::name();
    echo 'from user name';
  }
}
