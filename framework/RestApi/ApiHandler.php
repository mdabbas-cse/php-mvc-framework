<?php

namespace LaraCore\Framework\RestApi;

use LaraCore\Framework\Configuration;

class ApiHandler
{

  /**
   * @var $request
   */
  public $request;

  /**
   * @var $response
   */
  public $response;
  public function __construct($request, $response)
  {
    $this->request = $request;
    $this->response = $response;
  }

  /**
   * Method to set api header, cors, content type etc
   * 
   * @return void
   */
  public function setApiHeaderWithAuth()
  {
    // Enable CORS (Cross-Origin Resource Sharing)
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

    // Set content type to JSON
    $this->request->setJsonHeader();

    $config = Configuration::get('api-token');
    if (!$config['check']) {
      return;
    }

    // Check for api token in header
    $api_token = $this->request->isHttpAuthorizedOrFail();
    if (!$api_token) {
      // No Authorization header provided
      $this->request->setUnauthorizedHeader();
      $this->response->jsonResponse(
        ['error' => 'No Authorization header provided'],
        401
      );
    }
    if (empty($api_token)) {
      // Invalid or missing Bearer token
      $this->request->setUnauthorizedHeader();
      $this->response->jsonResponse(
        ['error' => 'Invalid or missing Bearer token'],
        401
      );
    }
    // For demonstration, assume a hardcoded valid token
    $apiKey = $config['key']; // api_token

    if ($api_token !== $apiKey) {
      // Invalid token
      $this->request->setUnauthorizedHeader();
      $this->response->jsonResponse(
        ['error' => 'Invalid token'],
        401
      );
    }
  }
}