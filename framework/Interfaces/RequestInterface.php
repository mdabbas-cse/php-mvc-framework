<?php

namespace LaraCore\Framework\Interfaces;

interface RequestInterface
{
  /**
   * Uri which is used to get the uri
   * @return string
   */
  public function uri(): string;
  /**
   * Method which is used to get the method
   * @return string
   */
  public function method(): string;

  /**
   * All which is used to get all the request
   * @return object
   */
  public function all(): object;

  /**
   * Get which is used to get the request
   * @return bool
   */
  public function isPost(): bool;

  /**
   * Get which is used to get the request
   * @return bool
   */
  public function isGet(): bool;

  /**
   * Get which is used to get the request
   * @return bool
   */
  public function isPut(): bool;

  /**
   * Get which is used to get the request
   * @return bool
   */
  public function isDelete(): bool;

  /**
   * Get which is used to get the request
   * @return bool
   */
  public function isPatch(): bool;

  /**
   * Get which is used to get the request
   * @return bool
   */
  public function isOptions(): bool;

  /**
   * Get which is used to get the request
   * @return bool
   */
  public function isHead(): bool;

}