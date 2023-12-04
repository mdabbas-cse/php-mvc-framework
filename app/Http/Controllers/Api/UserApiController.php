<?php

namespace LaraCore\App\Http\Controllers\Api;

use LaraCore\Framework\Controller;

class UserApiController extends Controller
{
  public function index($request, $response)
  {
    // generate 5 dummy users
    $data = [
      'success' => true,
      'users' => [
        [
          'id' => 1,
          'name' => 'John Doe',
          'email' => ''
        ],
        [
          'id' => 2,
          'name' => 'Jane Doe',
          'email' => ''
        ],
        [
          'id' => 3,
          'name' => 'John Smith',
          'email' => ''
        ],
        [
          'id' => 4,
          'name' => 'Jane Smith',
          'email' => ''
        ],
        [
          'id' => 5,
          'name' => 'John Doe',
          'email' => ''
        ],
      ]
    ];
    return $response->json($data);
  }

  public function user($request, $response)
  {
    $req = $request->all();
    $data = [
      'success' => true,
      'user' => [
        'id' => $req->id,
        'name' => 'John Doe',
        'email' => ''
      ]
    ];
    return $response->json($data);
  }
}