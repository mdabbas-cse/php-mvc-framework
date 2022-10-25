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
  }

  public function view($viewName, $data = [])
  {
    $this->view = new View();
    return $this->view->render($viewName, $data);
  }
}
