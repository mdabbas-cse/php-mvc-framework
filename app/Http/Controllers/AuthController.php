<?php

namespace MVC\App\Http\Controllers;

use MVC\App\Models\Users;
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
    $registerModel = new Users();
    $registerModel->loadData($request->getBody());

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
    $registerModel = new Users();
    if ($request->isPost()) {
      $this->validation(
        $request,
        [
          'firstname' => ['required'],
          'lastname' => ['required'],
          'email' => ['required', 'email'],
          'password' => ['required', ['min', 'min' => 2], ['max', 'max' => 12]],
          'confirmPassword' => ['required', ['match', 'match' => 'password']],
        ]
      );

      if (!$this->isValidate()) {
        return $this->view('auth/register');
      } else {
        $registerModel->loadData($request->getBody());
        $registerModel->save();
        // return $this->redirect('login');
      }

      // dd($request->getBody());
      // $registerModel->loadData($request->getBody());
      // if ($registerModel->validate() && $registerModel->register()) {
      //   // return $this->redirect('home');
      //   return 'success';
      // }
      // $model = $registerModel;
      // return $this->view('auth/register', compact('model'));
    }

    $model = $registerModel;
    $data = [
      'model' => $registerModel,
    ];

    // return $this->view('auth/register', $data);
  }
}
