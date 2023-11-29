<?php

/**
 * Application class for Lara Framework to control hold app config and run app 
 * to communicate with router, kernel, request, response, controller, model, view, 
 * @package Lara/Framework
 * 
 * @license MIT
 * 
 * @version 1.0.0
 * 
 * @since 1.0.0
 * 
 * @link 
 * 
 */

namespace LaraCore\Framework;

use LaraCore\Framework\Interfaces\RequestInterface;
use LaraCore\Framework\Routers\Router;
use LaraCore\Framework\Routers\RouterInterface;

final class Application
{

  /**
   * Application config
   * 
   * @var array
   */
  protected $config = [];

  /**
   * Application instance
   * 
   * @var Application
   */
  protected static $instance;

  /**
   * Application request
   * 
   * @var Request $request
   */
  protected $request;

  /**
   * Application response
   * 
   * @var Response $response
   */
  protected $response;

  /**
   * Application constructor
   * 
   * @param array $config
   * 
   * @return void
   */
  public function __construct()
  {
    $this->config = Configuration::get('app');
    self::$instance = $this;
    $this->request = new Request();
    $this->response = new Response();
    $this->setDefaultTimezone();
  }

  /**
   * Get application instance
   * 
   * @return Application
   */
  public static function getInstance()
  {
    return self::$instance;
  }

  /**
   * Get application config
   * 
   * @return array
   */
  public function getConfig()
  {
    return $this->config;
  }

  /**
   * Set default timezone
   */
  protected function setDefaultTimezone()
  {
    date_default_timezone_set($this->config['timezone']);
  }

  /**
   * Run application
   * 
   * @return void
   */
  public function run()
  {
    $this->loadHelpers();
    $this->registerErrorHandler();
    $this->registerShutdownHandler();
    $this->registerRoutes();
    $this->registerMiddlewares();
    $this->dispatch();
  }

  /**
   * Register error handler
   * 
   * @return void
   */
  protected function registerErrorHandler()
  {
    set_error_handler([$this, 'errorHandler']);
  }

  /**
   * Register shutdown handler
   * 
   * @return void
   */
  protected function registerShutdownHandler()
  {
    register_shutdown_function([$this, 'shutdownHandler']);
  }

  /**
   * Register routes
   * 
   * @return void
   */
  protected function registerRoutes()
  {
    require_once(ROOT . DS . 'routes' . DS . 'web.php');
  }

  /**
   * Register middlewares
   * 
   * @return void
   */
  protected function registerMiddlewares()
  {
    require_once(ROOT . DS . 'app' . DS . 'Http' . DS . 'Kernel.php');
  }

  /**
   * Dispatch route
   * 
   * @return void
   */
  protected function dispatch()
  {
    Router::dispatch($this->request, $this->response);
  }

  /**
   * Error handler
   * 
   * @param int $errno
   * @param string $errstr
   * @param string $errorFile
   * @param int $errorLine
   * 
   * @return bool
   */
  public function errorHandler($errno, $errstr, $errorFile, $errorLine)
  {
    $this->logError($errno, $errstr, $errorFile, $errorLine);
    // $this->displayError($errno, $errstr, $errorFile, $errorLine);

    return true;

  }
  protected function logError($errno, $errstr, $errorFile, $errorLine)
  {
    error_log(
      "[" . date('Y-m-d H:i:s') . "] Error number: {$errno} | Error string: {$errstr} | Error file: {$errorFile} | Error line: {$errorLine} \n",
      3,
      ROOT . DS . 'storage' . DS . 'logs' . DS . 'error.log'
    );

  }
  protected function displayError($severity, $messages, $file, $line, $responseCode = 500)
  {
    http_response_code($responseCode);
    if ($this->config['debug']) {
      include_once(ROOT . DS . 'resources' . DS . 'error-templates' . DS . 'error_template.php');
    } else {
      include_once(ROOT . DS . 'resources' . DS . 'error-templates' . DS . 'error_template.php');
    }
    exit();
  }

  /**
   * Shutdown handler
   * 
   * @return void
   */
  public function shutdownHandler()
  {
    $error = error_get_last();
    if (!empty($error) && $error['type'] === E_ERROR) {
      $this->errorHandler($error['type'], $error['message'], $error['file'], $error['line']);
    }
  }

  /**
   * Get application root path
   */
  public static function rootPath()
  {
    return ROOT;
  }

  /**
   * Get application resource path
   */

  public static function resourcePath()
  {
    return ROOT . DS . 'resources';
  }

  /**
   * Get application path info
   */
  public static function pathInfo()
  {
    return pathinfo(ROOT);
  }

  /**
   * Load helper functions
   */
  public static function loadHelpers()
  {
    require_once(ROOT . DS . 'framework' . DS . 'Helpers.php');
  }

  /**
   * Summary of __destruct
   */
  public function __destruct()
  {
    // $this->run();
  }


}