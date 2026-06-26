<?php

namespace LaraCore\App\Http\Middlewares;

use LaraCore\Framework\Request;

class AdminMiddleware
{
    public function handle(Request $request, $next)
    {
        $user = $_SESSION['user'] ?? null;

        if (!$user || empty($user['role']) || $user['role'] !== 'admin') {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
