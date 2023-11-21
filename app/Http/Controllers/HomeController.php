<?php

namespace LaraCore\App\Http\Controllers;

use LaraCore\Framework\Controller;

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
      'name' => 'LaraCore Framework'
    ];
    // return view('home', $data);
    return $this->view('home', $data);
  }
}
