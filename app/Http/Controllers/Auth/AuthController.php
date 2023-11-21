<?php

namespace LaraCore\App\Http\Controllers\Auth;

use LaraCore\App\Models\Users;
use LaraCore\Framework\Controller;
use LaraCore\Framework\Helpers\FlashMessages;
use LaraCore\Framework\Request;
use LaraCore\Framework\Response;

class AuthController extends Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->setLayout('auth');
  }
  public function loginForm()
  {
    return $this->view('auth/login');
  }

  public function login(Request $request)
  {
    $users = new Users();
    $users->loadData($request->getBody());
    $user = $users->login();
    dd($user);
    return $this->view('auth/login', compact('model'));
  }

  public function registerForm()
  {
    $users = new Users();
    dd($users->getAll());
    return $this->view('auth/register');
  }

  public function register(Request $request, Response $response)
  {
    $users = new Users();
    if ($request->isPost()) {
      $this->validation(
        $request,
        [
          'firstname' => ['required'],
          'lastname' => ['required'],
          'email' => ['required', 'email', ['unique', 'class' => Users::class]],
          'password' => ['required', ['min', 'min' => 2], ['max', 'max' => 12]],
          'confirmPassword' => ['required', ['match', 'match' => 'password']],
        ]
      );

      if ($this->isValidate()) {
        $users->firstname = $request->input('firstname');
        $users->lastname = $request->lastname;
        $users->email = $request->email;
        $users->password = $request->password;
        $users->save();
        (new FlashMessages)->setFlash('success', 'User created successfully');
        return $response->redirect('auth-login');
      }
      $response->redirect('auth-register');
    }
  }
}
