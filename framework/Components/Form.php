<?php

/**
 * @package    MVC
 * @subpackage Framework
 * @author     Md. Abbas Uddin
 * @version    1.0.0
 */
/**
 * Class Form
 * @package MVC\Framework\Components
 * @description This class is used to create form dynamically

 * @property string $action
 * @property string $method
 * @property string $class
 * @property string $id
 * @property string $enctype
 * @property string $acceptCharset
 * @property string $autocomplete
 * @property string $novalidate
 * @property string $target
 * @property string $style
 */

class  Form
{
  public function __construct($action, $method = 'post', $class = '', $id = '', $enctype = '', $acceptCharset = '', $autocomplete = '', $novalidate = '', $target = '', $style = '')
  {
    $this->action = $action;
    $this->method = $method;
    $this->class = $class;
    $this->id = $id;
    $this->enctype = $enctype;
    $this->acceptCharset = $acceptCharset;
    $this->autocomplete = $autocomplete;
    $this->novalidate = $novalidate;
    $this->target = $target;
    $this->style = $style;
  }
}
