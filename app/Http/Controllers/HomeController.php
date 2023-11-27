<?php

namespace LaraCore\App\Http\Controllers;


use LaraCore\Framework\Controller;
use LaraCore\Framework\Request;
use LaraCore\Framework\Response;

class HomeController extends Controller
{
  // public function __construct()
  // {
  //   parent::__construct();
  // }
  public function index(Request $request, Response $response)
  {
    $data = [
      'title' => 'Home Page',
      'name' => 'LaraCore Framework'
    ];
    // return view('home', $data);
    return $this->view('home', $data);
  }
}