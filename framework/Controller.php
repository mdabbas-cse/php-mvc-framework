<?php

namespace MVC\Framework;

use MVC\Framework\Application;
use MVC\Framework\View;

class Controller extends Application
{
  protected $view;
  public function __construct()
  {
    parent::__construct();
    $this->view = new View();
  }
}
