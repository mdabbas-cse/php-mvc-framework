<?php

namespace MVC\App\Http\Controllers;

use MVC\App\Models\RegisterModel;
use MVC\Framework\Controller;
use MVC\Framework\Request;

class AuthController extends Controller
{
  public function login()
  {
    $this->setLayout('auth');
    return $this->view('auth/login');
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
