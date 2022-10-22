<?php

namespace MVC\App\Http\Controllers;

use MVC\Framework\Controller;

class Home extends Controller
{
  public function __construct()
  {
  }
  public function index()
  {
    $data = [
      'title' => 'Home Page',
      'name' => 'MVC Framework'
    ];
    return view('home', $data);
  }
}
