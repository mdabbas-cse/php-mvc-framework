<?php

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));
$autoload = ROOT . DS . 'vendor' . DS . 'autoload.php';
try {
  if (file_exists($autoload)) {
    require_once($autoload);
  } else {
    throw new Exception('Autoload file not found.');
  }
} catch (Exception $e) {
  echo $e->getMessage();
}

$bootstrap = ROOT . DS . 'framework' . DS . 'Bootstrap.php';

include_once($bootstrap);
// https: //github.com/spiral/framework/issues