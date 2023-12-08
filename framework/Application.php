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

use LaraCore\Framework\Console\Command;
use LaraCore\Framework\Dotenv\Dotenv;
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
   * All application config
   * 
   * @var array
   */
  protected $allConfig = [];

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
    self::$instance = $this;
    $this->request = new Request();
    $this->response = new Response();
    $this->loadDotEnv();
    $this->allConfig = Configuration::all();
    $this->loadAppConfig();
    $this->loadHelpers();
    $this->setDefaultTimezone();
    $this->registerErrorHandler();
    $this->registerShutdownHandler();
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
    $this->registerRoutes();
    $this->registerApiRoutes();
    $this->registerMiddlewares();
    $this->dispatch();
  }

  /**
   * Load application config
   * 
   * @return void
   */
  protected function loadAppConfig()
  {
    $this->config = $this->allConfig['app'];
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
    // call()
    include_file('routes/web.php');
  }

  /**
   * Register API routes
   * 
   * @return void
   */
  protected function registerApiRoutes()
  {
    Router::setApiPrefix('api');
    include_file('routes/api.php');
  }

  /**
   * Register middlewares
   * 
   * @return void
   */
  protected function registerMiddlewares()
  {
    include_file('app/Http/Kernel.php');
    // require_once(ROOT . DS . 'app' . DS . 'Http' . DS . 'Kernel.php');
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
    //TODO: display error
    // $this->displayError($errno, $errstr, $errorFile, $errorLine);

    return true;

  }
  protected function logError($errno, $errstr, $errorFile, $errorLine)
  {
    $dir = check_or_make_dir('storage/logs');

    $errorFilePath = $dir . DS . 'error.log';

    error_log(
      "[" . date('Y-m-d H:i:s') . "] Error number: {$errno} | Error string: {$errstr} | Error file: {$errorFile} | Error line: {$errorLine} \n",
      3,
      $errorFilePath
    );

  }
  protected function displayError($severity, $messages, $file, $line, $responseCode = 500)
  {
    http_response_code($responseCode);
    if ($this->config['debug']) {
      include_file('resources/error-templates/debug_template.php');
    } else {
      include_file('resources/error-templates/error_template.php');
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
   * Load helper functions
   */
  public function loadHelpers()
  {
    require_once(ROOT . DS . 'framework' . DS . 'Helpers.php');
  }

  public function loadDotEnv()
  {
    $dotenv = Dotenv::createImmutable(ROOT);
    $dotenv->load();
  }

  /**
   * Run console command
   * 
   * @param array $argv
   */
  public function runConsoleCommand($argv)
  {
    Command::run($argv);
  }
}