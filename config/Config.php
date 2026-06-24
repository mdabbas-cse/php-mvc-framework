<?php

return [
  'database' => [
    'dbname' => $_ENV['DB_DATABASE'],
    'username' => $_ENV['DB_USERNAME'],
    'password' => $_ENV['DB_PASSWORD'],
    'connection' => $_ENV['DB_HOST'],
    'port' => $_ENV['DB_PORT'],
    'options' => [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]
  ],
  'app' => [
    'title' => $_ENV['APP_NAME'],
    'defaultLayout' => $_ENV['APP_DEFAULT_LAYOUT'],
    'root' => $_ENV['APP_URL'],
    'debug' => $_ENV['APP_DEBUG'],
    'timezone' => $_ENV['APP_TIME_ZONE'],
  ],
  'auth' => [
    'session' => 'user_id',
    'remember' => 'user_r'
  ],
  'mail' => [
    'smtp_auth'    => true,
    'smtp_secure'  => $_ENV['MAIL_ENCRYPTION'] ?? '',
    'host'         => $_ENV['MAIL_HOST'] ?? 'localhost',
    'username'     => $_ENV['MAIL_USERNAME'] ?? '',
    'password'     => $_ENV['MAIL_PASSWORD'] ?? '',
    'port'         => $_ENV['MAIL_PORT'] ?? 1025,
    'html'         => true,
    'from_address' => $_ENV['MAIL_FROM_ADDRESS'] ?? 'hello@example.com',
    'from_name'    => $_ENV['MAIL_FROM_NAME'] ?? $_ENV['APP_NAME'] ?? 'LaraCore',
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
    'check' => $_ENV['API_TOKEN_CHECK'],
    'key' => $_ENV['API_TOKEN_KEY']
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