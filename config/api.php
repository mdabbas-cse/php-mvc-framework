<?php

/**
 * This file is part of the LaraCore Framework.
 * 
 * API config file.
 */
return [
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
  'check' => $_ENV['API_TOKEN_CHECK'],
  'key' => $_ENV['API_TOKEN_KEY']
];