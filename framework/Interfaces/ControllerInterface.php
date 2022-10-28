<?php

namespace MVC\Framework\Interfaces;

use MVC\Framework\Request;

interface ControllerInterface
{
  /**
   * 
   */
  public function view($viewName, $data = []);

  public function setLayout($layout);

  public function validation(Request $request, $data = []);
}
