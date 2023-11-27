<?php

namespace LaraCore\App\Http\Middlewares;

use LaraCore\Framework\Request;

class AuthMiddleware
{
  public function handle(Request $request, $next)
  {
    if (!isset($_SESSION['user'])) {
      return redirect()->route('welcome');
    }
    return $next($request);
  }
}