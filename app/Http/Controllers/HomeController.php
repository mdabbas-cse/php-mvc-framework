<?php

namespace Lora\Core\App\Http\Controllers;

use Lora\Core\Framework\Controller;

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
      'name' => 'Lora\Core Framework'
    ];
    // return view('home', $data);
    return $this->view('home', $data);
  }
}
