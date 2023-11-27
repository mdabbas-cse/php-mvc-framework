<?php

namespace LaraCore\Framework\Routers;

use LaraCore\Framework\Request;

interface RouterInterface
{
  /**
   * Summary of load which is used to load the routes file
   * 
   * @param mixed $file
   * @return @RouterInterface
   */
  public static function load($file): RouterInterface;

  /**
   * Summary of callRouter which is used to call the router
   * 
   * @param mixed $uri
   * @param mixed $controller
   * @return mixed
   */
  public function callRouter($uri, $controller): mixed;

  /**
   * Summary of GET which is used to get the callback function
   * 
   * @param mixed $uri
   * @param mixed $controller
   * @return @RouterInterface
   */
  public function get($uri, $controller): RouterInterface;

  /**
   * Summary of POST which is used to get the callback function
   * 
   * @param mixed $uri
   * @param mixed $controller
   * @return @RouterInterface
   */
  public function post($uri, $controller): RouterInterface;

  /**
   * Summary of PUT which is used to get the callback function
   * 
   * @param mixed $uri
   * @param mixed $controller
   * @return @RouterInterface
   */
  public function put($uri, $controller): RouterInterface;

  /**
   * Summary of DELETE which is used to get the callback function
   * 
   * @param mixed $uri
   * @param mixed $controller
   * @return @RouterInterface
   */
  public function delete($uri, $controller): RouterInterface;

  /**
   * Summary of PATCH which is used to get the callback function
   * 
   * @param mixed $uri
   * @param mixed $controller
   * @return @RouterInterface
   */
  public function patch($uri, $controller): RouterInterface;

  /**
   * Summary of OPTIONS which is used to get the callback function
   * 
   * @param mixed $uri
   * @param mixed $controller
   * @return @RouterInterface
   */
  public function options($uri, $controller): RouterInterface;

  /**
   * Summary of any which is used to any request method and to get the callback function
   * 
   * @param mixed $uri
   * @param mixed $controller
   * @return @RouterInterface
   */
  public function any($uri, $controller): RouterInterface;

  /**
   * Summary of routing group which is used to group the routes and add prefix to the routes
   * get the callback function
   * 
   * @param mixed $prefix
   * @return @RouterInterface
   */
  public function group($prefix, $callback): RouterInterface;


  /**
   * Summary of middleware which is used to add middleware to the routes
   * 
   * @param string|middleware instance $middleware
   * @return @RouterInterface
   */
  public function middleware($middleware): RouterInterface;

  /**
   * Summary of set route name which is used to set the route name
   * 
   * @param string $name
   * @return @RouterInterface
   */
  public function name(string $name): RouterInterface;


}