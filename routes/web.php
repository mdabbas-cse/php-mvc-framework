<?php

use MVC\App\Http\Controllers\Home;

$routes->get('/', [Home::class, 'index']); // for home page
$routes->get('about', 'app/Http/Controllers/about.php');
$routes->get('about/culture', 'app/Http/Controllers/about-culture.php');
$routes->get('contact', 'app/Http/Controllers/contact.php');
$routes->get('names', 'app/Http/Controllers/add-name.php');
$routes->get('users', 'app/Http/Controllers/users.php');
$routes->get('users/([0-9]+)', 'app/Http/Controllers/user.php');
$routes->get('users/([0-9]+)/edit', 'app/Http/Controllers/edit-user.php');
$routes->get('users/([0-9]+)/delete', 'app/Http/Controllers/delete-user.php');
$routes->get('users/([0-9]+)/posts', 'app/Http/Controllers/user-posts.php');
// dd($routes);
