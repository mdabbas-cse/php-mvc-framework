<?php

namespace LaraCore\Framework\Console;

class ApiKey {
  private static function generateKey() {
    $key = base64_encode(random_bytes(32));
    $key = substr($key, 0, 32);
    $key = str_replace('/', '', $key);
    $key = str_replace('+', '', $key);
    $key = str_replace('=', '', $key);
    return $key;
  }

  /**
   * run command method
   */
  public static function generate() {
    $newKey = self::generateKey();

    $filePath = ROOT.DS.'.env';
    $contents = file_get_contents($filePath);
    $key = 'API_TOKEN_KEY';

    // Find and replace the specified key with the new value
    $contents = preg_replace("/$key=.*/", "$key=$newKey", $contents);

    // Write the updated contents back to the .env file
    $result = file_put_contents($filePath, $contents);

    if($result) {
      Log::success("Api key generated successfully");
    } else {
      Log::error("Api key generation failed");
    }
  }
}