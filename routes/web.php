<?php

use MVC\App\Http\Controllers\AuthController;
use MVC\App\Http\Controllers\HomeController;
use MVC\App\Http\Controllers\UserController;

$routes->get('/', [HomeController::class, 'index']); // for home page
$routes->get('user', function () {
  echo 'user';
}); // callback function
$routes->get('contact', 'contact'); // for access view without any call back function
$routes->post('contact', [UserController::class, 'store']); // for post request

$routes->get('auth', [AuthController::class, 'login']);


$routes->get('about', 'app/Http/Controllers/about.php');
$routes->get('about/culture', 'app/Http/Controllers/about-culture.php');

$routes->get('names', 'app/Http/Controllers/add-name.php');
$routes->get('users', 'app/Http/Controllers/users.php');
$routes->get('users/([0-9]+)/show', [UserController::class, 'show']);
$routes->get('users/([0-9]+)/edit', 'app/Http/Controllers/edit-user.php');
$routes->get('users/([0-9]+)/delete', 'app/Http/Controllers/delete-user.php');
$routes->get('users/([0-9]+)/posts', 'app/Http/Controllers/user-posts.php');
// dd($routes);
