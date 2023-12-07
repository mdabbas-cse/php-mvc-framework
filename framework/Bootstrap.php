<?php

use LaraCore\Framework\Application;

session_start();

$app = new Application();

$app->run();

session_destroy();