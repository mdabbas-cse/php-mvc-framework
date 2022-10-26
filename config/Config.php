<?php

return [
  'database' => [
    'dbname' => 'mvc',
    'username' => 'root',
    'password' => '1234',
    'connection' => 'mysql:host=localhost:3307;',
    'options' => [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]
  ],
  'app' => [
    'title' => 'My Todo',
    'defaultLayout' => 'default',
    'web-root' => 'http://pro.af/',
  ],
  'auth' => [
    'session' => 'user_id',
    'remember' => 'user_r'
  ],
  'mail' => [
    'smtp_auth' => true,
    'smtp_secure' => 'tls',
    'host' => 'smtp.gmail.com',
    'username' => 'your email',
    'password' => 'your password',
    'port' => 587,
    'html' => true
  ],
  'twig' => [
    'debug' => true
  ],
  'csrf' => [
    'key' => 'csrf_token'
  ],
  'remember' => [
    'cookie_name' => 'hash',
    'cookie_expiry' => 604800
  ],
  'session' => [
    'session_name' => 'user',
    'token_name' => 'token'
  ],
  'pagination' => [
    'per_page' => 5
  ],
  'debug' => false,
];
