<?php

namespace Lora\Core\Framework\Interfaces;

use Lora\Core\Framework\Request;

interface ControllerInterface
{
  /**
   * 
   */
  public function view($viewName, $data = []);

  public function setLayout($layout);

  public function validation(Request $request, $data = []);
}
