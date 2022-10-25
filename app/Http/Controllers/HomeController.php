<?php

namespace MVC\App\Http\Controllers;

use MVC\Framework\Controller;

class HomeController extends Controller
{
  // public function __construct()
  // {
  //   parent::__construct();
  // }
  public function index()
  {
    $data = [
      'title' => 'Home Page',
      'name' => 'MVC Framework'
    ];
    // return view('home', $data);
    return $this->view('home', $data);
  }
}
