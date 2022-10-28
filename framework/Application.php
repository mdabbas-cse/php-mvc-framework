<?php

namespace MVC\Framework;

class Application
{
  public function __construct()
  {
    $this->_set_reporting();
    $this->_unregister_globals();
  }

  private function _set_reporting()
  {
    $debug = Configuration::get('app')['debug'];
    if ($debug) {
      error_reporting(E_ALL);
      ini_set('display_errors', 0);
      ini_set('log_errors', 1);
      ini_set('error_log', ROOT . DS . 'resources' . DS . 'logs' . DS . 'error.log');
    } else {
      error_reporting(E_ALL);
      ini_set('display_errors', 1);
    }
  }

  private function _unregister_globals()
  {
    if (ini_get('register_globals')) {
      $globalsAry = ['_SESSION', '_COOKIE', '_GET', '_POST', '_REQUEST', '_SERVER', '_ENV', '_FILES'];
      foreach ($globalsAry as $g) {
        foreach ($GLOBALS[$g] as $k => $v) {
          if ($GLOBALS[$k] === $v) {
            unset($GLOBALS[$k]);
          }
        }
      }
    }
  }
}
