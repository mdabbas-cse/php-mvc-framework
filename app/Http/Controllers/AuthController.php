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

  public function login(Request $request)
  {
    $data = [
      'errors' => [],
    ];
    $this->setLayout('auth');
    $registerModel = new RegisterModel();
    $registerModel->loadData($request->getBody());
    // if ($registerModel->validate() && $registerModel->login()) {
    //   return $this->redirect('home');
    // }
    // dd($registerModel);
    $model = $registerModel;

    return $this->view('auth/login', compact('model'));
  }


  public function registerForm()
  {
    $this->setLayout('auth');
    return $this->view('auth/register');
  }

  public function register(Request $request)
  {
    $registerModel = new RegisterModel();
    if ($request->isPost()) {
      dd($request->getBody());
      $registerModel->loadData($request->getBody());
      if ($registerModel->validate() && $registerModel->register()) {
        // return $this->redirect('home');
        return 'success';
      }
      $data = [
        'model' => $registerModel,
      ];
      dd($data);
      return $this->view('auth/register', $data);
    }
    $data = [
      'model' => $registerModel,
    ];
    return $this->view('auth/register', $data);
  }
}
