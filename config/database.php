<?php

/**
 * This file is part of the LaraCore Framework.
 * Database config file.
 */

return [
  // 'driver' => $_ENV['DB_DRIVER'],
  // 'host' => $_ENV['DB_HOST'],
  // 'port' => $_ENV['DB_PORT'],
  // 'database' => $_ENV['DB_DATABASE'],
  // 'username' => $_ENV['DB_USERNAME'],
  // 'password' => $_ENV['DB_PASSWORD'],
  // 'charset' => $_ENV['DB_CHARSET'],
  // 'collation' => $_ENV['DB_COLLATION'],
  // 'prefix' => $_ENV['DB_PREFIX'],
  // 'strict' => $_ENV['DB_STRICT'],
  // 'engine' => $_ENV['DB_ENGINE'],
  'dbname' => $_ENV['DB_DATABASE'],
  'username' => $_ENV['DB_USERNAME'],
  'password' => $_ENV['DB_PASSWORD'],
  'connection' => $_ENV['DB_HOST'],
  'port' => $_ENV['DB_PORT'],
  'options' => [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
  ]
];