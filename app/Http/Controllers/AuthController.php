<?php

namespace MVC\App\Http\Controllers;

use MVC\App\Models\RegisterModel;
use MVC\Framework\Controller;
use MVC\Framework\Request;

class AuthController extends Controller
{
  public function loginForm()
  {
    $this->setLayout('auth');
    $data = [
      'errors' => [],
    ];
    return $this->view('auth/login', compact('data'));
  }

  public function registerForm()
  {
    $this->setLayout('auth');
    return $this->view('auth/register');
  }

  public function register(Request $request)
  {
    $registerModel = new RegisterModel();
    $registerModel->loadData($request->getBody());
    if ($registerModel->validate() && $registerModel->register()) {
      return 'success';
    }
    dd($registerModel->errors);
    $registerModel->register();
    return $this->view('auth/register');
  }
}
