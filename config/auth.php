<?php

/**
 * This file is part of the LaraCore Framework.
 * 
 * Auth config file.
 */

return [
  'model' => 'App\Models\User',
  'table' => 'users',
  'username' => 'email',
  'password' => 'password',
  'remember' => 'remember_token',
  'providers' => [
    'users' => [
      'driver' => 'eloquent',
      'model' => 'App\Models\User',
    ],
  ],
  'passwords' => [
    'users' => [
      'provider' => 'users',
      'email' => 'auth.emails.password',
      'table' => 'password_resets',
      'expire' => 60,
    ],
    'session' => 'user_id',
    'remember' => 'user_r'
  ],
  'redirects' => [
    'login' => 'login',
    'logout' => 'logout',
    'register' => 'register',
    'home' => 'home',
  ],
];