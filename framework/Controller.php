<?php

namespace Lora\Core\Framework;

use Lora\Core\Framework\Application;
use Lora\Core\Framework\Validation;
use Lora\Core\Framework\Interfaces\ControllerInterface;
use Lora\Core\Framework\View;

abstract class Controller extends Application implements ControllerInterface
{
  protected $view;
  protected $validate;
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

  public function validation(Request $request, $data = [])
  {
    $this->validate = new Validation($request->getBody(), $data);
    return $this->validate->checkValidation();
  }
  public function isValidate()
  {
    return $this->validate->isValidate();
  }
  public function errors()
  {
    return $this->validate->getErrors();
  }
}
