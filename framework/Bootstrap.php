<?php

use LaraCore\Framework\Application;

$helpers = ROOT . DS . 'framework' . DS . 'Helpers.php';

include_once($helpers);

session_start();

$app = new Application();

$app->run();

session_destroy();