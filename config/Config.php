<?php

return [
  'database' => [
    'dbname' => 'mvc',
    'username' => 'root',
    'password' => '1234',
    'connection' => 'localhost',
    'port' => 3307,
    'options' => [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]
  ],
  'app' => [
    'title' => 'My Todo',
    'defaultLayout' => 'default',
    'root' => 'http://lora-core.af/',
    'debug' => false,
    'timezone' => 'Asia/Dhaka'
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
];
