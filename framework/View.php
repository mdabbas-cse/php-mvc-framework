<?php

namespace LaraCore\Framework;

class View
{

  public $_head;
  public $_body;
  public $_script;
  public $_siteTitle = '';
  public $_outBuffer;
  public $_layout = 'default';

  public $errors = [];

  public function render($viewName, $data = [])
  {
    extract($data);
    $viewAry = explode('/', $viewName);
    $viewString = implode(DS, $viewAry);
    $resourceFile = ROOT . DS . 'resources' . DS . 'views';
    if (file_exists($resourceFile . DS . $viewString . '.php')) {
      include($resourceFile . DS . $viewString . '.php');
      include($resourceFile . DS . 'layouts' . DS . $this->_layout . '.php');
    } else {
      die('The view "' . $viewName . '" does not exists!');
    }
  }

  public function content($type)
  {
    if ($type === 'head') {
      return $this->_head;
    } elseif ($type === 'body') {
      return $this->_body;
    } elseif ($type === 'script') {
      return $this->_script;
    }
    return false;
  }

  public function start($type)
  {
    $this->_outBuffer = $type;
    ob_start();
  }

  public function end()
  {
    if ($this->_outBuffer == 'head') {
      $this->_head = ob_get_clean();
    } elseif ($this->_outBuffer == 'body') {
      $this->_body = ob_get_clean();
    } elseif ($this->_outBuffer == 'script') {
      $this->_script = ob_get_clean();
    } else {
      die('Please! run first start() method.');
    }
  }

  public function siteTitle()
  {
    return $this->_siteTitle;
  }

  public function setSiteTile($title)
  {
    $this->_siteTitle = $title;
  }

  public function setLayout($path)
  {
    $this->_layout = $path;
  }

  /**
   * @method for setErrors 
   * 
   * @param $errors
   * @return void
   */
  public function setErrors($errors)
  {
    $this->errors = $errors;
  }

  /**
   * @method for getErrors 
   * 
   * @return array
   */
  public function errors()
  {
    return $this->errors;
  }

  /**
   * @method for hasErrors 
   * 
   * @return bool
   */
  public function hasErrors()
  {
    return !empty($this->errors);
  }

  /**
   * @method for get error by key 
   * 
   * @param $key
   * @return string
   */
  public function error($key)
  {
    return $this->errors[$key] ?? '';
  }

  /**
   * @method for check error by key
   * 
   * @param $key
   * @return bool
   */
  public function hasError($key)
  {
    return isset($this->errors[$key]);
  }
}