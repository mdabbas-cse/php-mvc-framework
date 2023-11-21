<?php

namespace LaraCore\Framework\Interfaces;

use LaraCore\Framework\Request;

interface ControllerInterface
{
  /**
   * 
   */
  public function view($viewName, $data = []);

  public function setLayout($layout);

  public function validation(Request $request, $data = []);
}
