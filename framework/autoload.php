<?php

function autoload($filename)
{
  $file = ROOT . DS . $filename . '.php';

  if (file_exists($file)) {
    require_once($file);
  } else {
    throw new Error($file . ' not found!😥');
  }
}

spl_autoload_register('autoload');
