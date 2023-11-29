<?php

namespace LaraCore\Framework\Db\FluidOrm\EntityManager;

interface EntityManagerInterface
{
  /**
   * Get the crud object which will expose all the method within our crud class
   * 
   * @return Object
   */
  public function getCrud(): object;
}