<?php

namespace LaraCore\App\Http\Controllers;

use LaraCore\Framework\Controller;
use LaraCore\Framework\Request;

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
      $data = $request->all();
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

  public function contact(Request $request)
  {
    dd($request->all());
  }
}