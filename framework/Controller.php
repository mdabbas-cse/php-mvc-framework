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

  public function view($viewName, $data = [])
  {
    return $this->view->render($viewName, $data);
  }

  public function setLayout($layout)
  {
    $this->view->setLayout($layout);
  }
}
