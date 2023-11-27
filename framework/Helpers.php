<?php

use LaraCore\Framework\Configuration;
use LaraCore\Framework\Helpers\Errors;
use LaraCore\Framework\Helpers\Values;
use LaraCore\Framework\Response;
use LaraCore\Framework\View;

if (!function_exists('dd')) {
  function dd(...$args)
  {
    $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 1);
    $caller = $trace[0];

    echo '<style>
              .dd-container {
                  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
                  background-color: #f3f3f3;
                  padding: 20px;
                  border-radius: 5px;
                  border: 1px solid #ccc;
                  margin: 20px;
                  position: relative;
              }

              .dd-location {
                  position: absolute;
                  top: 5px;
                  right: 5px;
                  color: #888;
                  font-size: 12px;
              }

              .dd-variable {
                  margin-bottom: 15px;
              }

              .dd-content {
                  white-space: pre-wrap;
                  word-wrap: break-word;
                  background-color: #fff;
                  padding: 15px;
                  border: 1px solid #ddd;
                  border-radius: 8px;
                  margin-top: 10px;
                  color: #333;
              }

              .dd-code-location {
                  margin-top: 10px;
                  font-size: 14px;
                  color: #555;
              }
              .dd-err-file {
                  background-color: #fff;
                  border: 1px solid #ddd;
                  color: #6e6e6e;
                  padding: 12px;
                  border-radius: 8px;
              }
           </style>';

    echo '<div class="dd-container">';
    echo '<h2 style="color: #333; border-bottom: 1px solid #ddd;">Dumped Data</h2>';
    echo '<div class="dd-variable dd-err-file">';
    echo 'Called in: <strong>' . $caller['file'] . '</strong> on line <strong>' . $caller['line'] . '</strong>';
    echo '</div>';
    foreach ($args as $arg) {
      echo '<div class="dd-variable">';
      echo '<pre class="dd-content">';
      highlight_var_dump($arg);
      echo '</pre>';
      echo '</div>';
    }
    echo '</div>';
    echo '<script>
              document.addEventListener("DOMContentLoaded", function() {
                  var ddContainers = document.querySelectorAll(".dd-container");
                  ddContainers.forEach(function(container) {
                      container.addEventListener("click", function() {
                          this.classList.toggle("collapsed");
                      });
                  });
              });
            </script>';

    exit;
  }
}
function highlight_var_dump($data)
{
  ob_start();
  var_dump($data);
  $output = ob_get_clean();

  // Highlight strings, integers, floats
  $output = preg_replace('/"(.*?)"/', '<span class="dd-string">"$1"</span>', $output);
  $output = preg_replace('/(int|float)\((.*?)\)/', '<span class="dd-$1"> $1($2)</span>', $output);

  echo $output;
}

if (!function_exists('view')) {
  /**
   * @function view
   * @param string $viewName
   * @param array $data 
   * @param option string $layout
   * @return View template
   */
  function view($viewName, $data = [], $layout = null)
  {
    $view = new View();
    if ($layout) {
      $view->setLayout($layout);
    }
    $viewRender = $view->render($viewName, $data);
    return $viewRender;
  }
}

if (!function_exists('redirect')) {
  /**
   * @function redirect
   * @param $path
   */
  function redirect()
  {
    $response = new Response();
    return $response;
  }
}

if (!function_exists('assets')) {
  /**
   * @function assets
   * @param $path
   * @return string
   */
  function assets($path)
  {
    $url = '/resources/assets/' . $path;
    return app_url($url);
  }
}


if (!function_exists('css')) {
  /**
   * @function css
   * @param $path
   * @return string
   */
  function css($path)
  {
    $url = '/resources/assets/css/' . trim($path, '/');
    return app_url($url);
  }
}

if (!function_exists('js')) {
  /**
   * @function js
   * @param $path
   * @return string
   */
  function js($path)
  {
    $url = '/resources/assets/js/' . trim($path, '/');
    return app_url($url);
  }
}

if (!function_exists('app_url')) {
  /**
   * @function app_url
   * @param $path
   * @return string
   */
  function app_url($path = null)
  {
    $config = Configuration::get('app');
    $url = trim($config['root'], '/');
    $path = trim($path, '/');
    return $path ? $url . '/' . $path : $url;
  }
}

/**
 * @function for get input value
 * @param $key
 * @return string
 */
if (!function_exists('old')) {
  function old($key)
  {
    return Values::get($key);
  }
}

/**
 * @function for get error message
 * @param $key
 * @return string
 */
if (!function_exists('errors')) {
  function errors($key)
  {
    return Errors::get($key);
  }
}

/**
 * @function for create or check path is exist
 * @param $path
 * @return string
 */
if (!function_exists('path')) {
  function path($path)
  {
    $path = trim($path, '/');
    $path = str_replace('/', DS, $path);
    $path = ROOT . DS . $path;

    if (!file_exists($path)) {
      mkdir($path);
    }
    return $path;
  }
}




include_once 'Components/InputsComponent.php';