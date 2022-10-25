<?php

namespace MVC\App\Http\Controllers;

use MVC\Framework\Controller;
use MVC\Framework\Request;

class UserController extends Controller
{
  public function index()
  {
    // return view('users');
    return $this->view('users');
  }
  public function store(Request $request)
  {
    $dd = $request->getBody();
    dd($dd);
  }


  public function show($id)
  {
    // return view('user', ['id' => $id]);
  }

  public function edit($id)
  {
    return view('edit-user', ['id' => $id]);
  }

  public function delete($id)
  {
    return view('delete-user', ['id' => $id]);
  }

  public function posts($id)
  {
    return view('user-posts', ['id' => $id]);
  }
}
