<?php

use LaraCore\App\Http\Controllers\Auth\AuthController;
use LaraCore\App\Http\Controllers\HomeController;
use LaraCore\App\Http\Controllers\UserController;
use LaraCore\App\Http\Middlewares\AuthMiddleware;
use LaraCore\Framework\Request;
use LaraCore\Framework\Response;
use LaraCore\Framework\Routers\Router;

// $routes->get('/welcome', 'welcome');

Router::get('/', function (Request $request, Response $response) {
  return view('welcome');
})->name('welcome');
Router::get('home', 'welcome1');

Router::get('home/id', [HomeController::class, 'index'])->middleware('auth')->name('home.index');

/**
 * Middleware Groups
 */
/** this middlewareGroup is not working **/
// Router::middlewareGroup('auth', function () {
//   Router::get('template', [HomeController::class, 'list']);
//   Router::get('template-list', [HomeController::class, 'list']);
// });

// $routes->get('users/{id}/{user}/show', [UserController::class, 'show']);
// $routes->get('users/{id:\d+}/{profile}', 'app/Http/Controllers/edit-user.php');

// dd($routes);