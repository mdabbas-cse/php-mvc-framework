<?php

use MVC\App\Http\Controllers\Auth\AuthController;
use MVC\App\Http\Controllers\UserController;

// $routes->get('/', [HomeController::class, 'index']); // for home page
$routes->get('/', [AuthController::class, 'loginForm']); // for home page
$routes->get('user', function () {
  echo 'user';
}); // callback function
$routes->get('contact', 'contact'); // for access view without any call back function
$routes->post('contact', [UserController::class, 'store']); // for post request

$routes->get('auth-register', [AuthController::class, 'registerForm'])->name('auth.registerFrom');
$routes->post('auth-register', [AuthController::class, 'register']);
$routes->get('auth-login', [AuthController::class, 'loginForm'])->name('auth.loginFrom');
$routes->post('auth-login', [AuthController::class, 'login'])->name('login');

$routes->get('users/{id}/{user}/show', [UserController::class, 'show']);
$routes->get('users/{id:\d+}/{profile}', 'app/Http/Controllers/edit-user.php');

// dd($routes);
