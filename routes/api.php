<?php

use LaraCore\App\Http\Controllers\Api\UserApiController;
use LaraCore\Framework\Routers\Router;

// Router::setApiPrefix('api');

// Router::middlewareGroup('authApi', function () {
//   Router::get('/user', [UserApiController::class, 'index']);
// });
Router::get('/user', [UserApiController::class, 'index']);
Router::get('/user/{id}', [UserApiController::class, 'user']);