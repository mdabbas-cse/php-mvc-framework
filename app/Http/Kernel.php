<?php


namespace LaraCore\App\Http;

class Kernel
{
  public static $middlewareAliases = [
    'auth' => \LaraCore\App\Http\Middlewares\AuthMiddleware::class,
    'guest' => \LaraCore\App\Http\Middlewares\GuestMiddleware::class,
    'admin' => \LaraCore\App\Http\Middlewares\AdminMiddleware::class,
    'authApi' => \LaraCore\App\Http\Middlewares\AuthApiMiddleware::class,
  ];
}