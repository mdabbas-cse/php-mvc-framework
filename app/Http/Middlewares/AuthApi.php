<?php

namespace LaraCore\App\Http\Middlewares;

class AuthApiMiddleware
{
  public static function handle($request)
  {
    // Get the API token from the request headers
    $token = $request->getHeader('Authorization');

    // Check if the token is present
    if (!$token) {
      self::unauthorizedResponse();
    }

    // Validate the token (you may want to implement a more secure validation mechanism)
    $isValidToken = self::validateToken($token);

    if (!$isValidToken) {
      self::unauthorizedResponse();
    }

    // The token is valid, continue with the request
  }

  private static function unauthorizedResponse()
  {
    // Handle unauthorized access (e.g., return a 401 Unauthorized response)
    header('HTTP/1.0 401 Unauthorized');
    echo 'Unauthorized';
    exit;
  }

  private static function validateToken($token)
  {
    // Validate the token against your storage (e.g., database)
    // Return true if the token is valid, false otherwise
    // Implement this method based on your specific authentication mechanism

    // For demonstration purposes, we'll assume a simple token validation
    $validTokens = [
      'eW91ci1hcGktdG9rZW4=',
    ]; // Store valid tokens securely

    return in_array($token, $validTokens);
  }
}