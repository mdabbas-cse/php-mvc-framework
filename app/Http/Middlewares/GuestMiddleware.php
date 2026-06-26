<?php

namespace LaraCore\App\Http\Middlewares;

use LaraCore\Framework\Request;

class GuestMiddleware
{
    public function handle(Request $request, $next)
    {
        if (isset($_SESSION['user'])) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
