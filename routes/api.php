<?php

use LaraCore\App\Http\Controllers\Api\UserApiController;
use LaraCore\Framework\Routers\Router;

// Router::setApiPrefix('api');

Router::get('/user', [UserApiController::class, 'index']);