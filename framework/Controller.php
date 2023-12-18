<?php

namespace LaraCore\Framework;

use LaraCore\Framework\Validation;
use LaraCore\Framework\Interfaces\ControllerInterface;
use LaraCore\Framework\View;

abstract class Controller implements ControllerInterface
{
  protected $view;
  protected $validate;
  public function __construct()
  {
    // parent::__construct();
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
    $this->validate = new Validation($request->all(), $data);
    $checkValidation = $this->validate->checkValidation();
    if (!$checkValidation) {
      $this->view->errors = $this->validate->getErrors();
      return false;
    }
    return $checkValidation;
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