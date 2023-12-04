<?php

return [
  'database' => [
    'dbname' => 'laracore',
    'username' => 'root',
    'password' => '',
    'connection' => 'localhost',
    'port' => 3306,
    'options' => [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]
  ],
  'app' => [
    'title' => 'My Todo',
    'defaultLayout' => 'default',
    'root' => 'http://php-mvc-framework.test/',
    'debug' => true,
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
  /**
   * Set API token
   * condition: true | false
   * key: base64_encode('laracore-api-token')
   * if condition is true then you need to pass api token in header
   * Authorization: Bearer base64_encode('laracore-api-token')
   * and set check to true
   * it's will set api token for all api routes
   * if you want to set api token for specific route then set check to false
   * and pass api token in header
   * and create middleware for that route
   * and set middleware in route
   */
  'api-token' => [
    'check' => false,
    'key' => 'bGFyYWNvcmUtYXBpLXRva2Vu'
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