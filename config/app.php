<?php
/**
 * This file is part of the LaraCore Framework.
 * App config file.
 */

return [
  'title' => $_ENV['APP_NAME'],
  'defaultLayout' => $_ENV['APP_DEFAULT_LAYOUT'],
  'root' => $_ENV['APP_URL'],
  'debug' => $_ENV['APP_DEBUG'],
  'timezone' => $_ENV['APP_TIME_ZONE'],
];