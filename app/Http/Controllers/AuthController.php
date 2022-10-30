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
    $users = new Users();
    $users->loadData($request->getBody());

    $model = $users;

    return $this->view('auth/login', compact('model'));
  }


  public function registerForm()
  {
    $this->setLayout('auth');
    return $this->view('auth/register');
  }

  public function register(Request $request)
  {
    $users = new Users();
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
        // dd($request->getBody());
        // $users->loadData($request->getBody());
        $users->firstname = $request->input('firstname');
        $users->lastname = $request->lastname;
        $users->email = $request->email;
        $users->password = $request->password;
        $users->save();
        // return $this->redirect('login');
      }

      // dd($request->getBody());
      // $users->loadData($request->getBody());
      // if ($users->validate() && $users->register()) {
      //   // return $this->redirect('home');
      //   return 'success';
      // }
      // $model = $users;
      // return $this->view('auth/register', compact('model'));
    }

    $model = $users;
    $data = [
      'model' => $users,
    ];

    // return $this->view('auth/register', $data);
  }
}
