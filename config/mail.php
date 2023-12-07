<?php

/**
 * This file is part of the LaraCore Framework.
 * 
 * Mail config file.
 * (c) 2022 - Present LaraCore Framework
 */

return [
  'driver' => $_ENV['MAIL_DRIVER'],
  'host' => $_ENV['MAIL_HOST'],
  'port' => $_ENV['MAIL_PORT'],
  'username' => $_ENV['MAIL_USERNAME'],
  'password' => $_ENV['MAIL_PASSWORD'],
  'encryption' => $_ENV['MAIL_ENCRYPTION'],
  'from' => [
    'address' => $_ENV['MAIL_FROM_ADDRESS'],
    'name' => $_ENV['MAIL_FROM_NAME'],
  ],
  'sendmail' => '/usr/sbin/sendmail -bs',
  'pretend' => false,

];