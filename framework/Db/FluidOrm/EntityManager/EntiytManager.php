<?php

namespace Framework\Db\FluidOrm\EntityManager;

use Framework\Db\FluidOrm\EntityManager\CrudInterface;

class EntityManager implements EntityManagerInterface
{
  protected CrudInterface $crud;

  /**
   * Summary of __construct
   * 
   * @param CrudInterface $crud
   */
  public function __construct(CrudInterface $crud)
  {
    $this->crud = $crud;
  }

  /**
   * @InheritDoc
   * 
   * @return Object
   */
  public function getCrud(): object
  {
    return $this->crud;
  }
}
