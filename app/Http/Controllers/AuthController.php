<?php

namespace MVC\App\Http\Controllers;

use MVC\Framework\Controller;

class AuthController extends Controller
{
  public function login()
  {
    $this->setLayout('auth');
    return $this->view('auth/login');
  }
  public function register()
  {
    return $this->view('register');
  }
}
