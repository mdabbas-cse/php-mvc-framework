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
    if ($request->isPost()) {
      $data = $request->getBody();
      dd($data);
    }
  }


  public function show(Request $request)
  {
    dd($request->getParam('id'));
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
