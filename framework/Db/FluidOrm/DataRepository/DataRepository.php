<?php

namespace Framework\Db\FluidOrm\DataRepository;

use Framework\Db\FluidOrm\DataRepository\DataRepositoryInterface;
use Framework\Db\FluidOrm\EntityManager\EntityManagerInterface;
use Lora\Core\Framework\Db\ExceptionTraits\InvalidArgumentException;

class DataRepository implements DataRepositoryInterface
{
  use InvalidArgumentException;
  protected EntityManagerInterface $entityManager;

  /**
   * DataRepository constructor.
   * 
   * @param EntityManagerInterface $entityManager
   */
  public function __construct(EntityManagerInterface $entityManager)
  {
    $this->entityManager = $entityManager;
  }

  /**
   * @inheritDoc
   * 
   * @return array
   */
  public function find(int $id): array
  {
    $this->isEmpty($id);
    try {
      $entity = $this->findOneBy(['id' => $id]);
      return $entity;
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  /**
   * @inheritDoc
   * 
   * @return array
   */
  public function findAll(): array
  {
    try {
      $entity = $this->entityManager->getCrud()->read();
      return $entity;
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  /**
   * @inheritDoc
   * 
   * @return array
   */
  public function findBy(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []): array
  {
    try {
      $entity = $this->entityManager->getCrud()->read($selectors, $conditions, $parameters, $optional);
      return $entity;
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  /**
   * @inheritDoc
   * 
   * @return array
   */
  public function findOneBy(array $conditions): array
  {
    $this->isArray($conditions);
    try {
      $entity = $this->entityManager->getCrud()->read([], $conditions);
      return $entity;
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  /**
   * @inheritDoc
   * 
   * @return Object
   */
  public function findObjectBy(array $conditions = [], array $selectors = []): object
  {
    return (object) [];
  }

  /**
   * @inheritDoc
   * 
   * @return array
   */
  public function findBySearch(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []): array
  {
    try {
      $entity = $this->entityManager->getCrud()->read($selectors, $conditions, $parameters, $optional);
      return $entity;
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  /**
   * @inheritDoc
   * 
   * @return array
   */
  public function findByIdAndDelete(array $conditions): bool
  {
    $this->isArray($conditions);
    try {
      $result = $this->findOneBy($conditions);
      if ($result !== null && count($result) > 0) {
        $delete = $this->entityManager->getCrud()->delete($conditions);
        if ($delete) {
          return true;
        }
      }
      return false;
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  /**
   * @inheritDoc
   * 
   * @return array
   */
  public function findByIdAndUpdate(array $fields = [], int $id): bool
  {
    $this->isArray($fields);
    try {
      $getId = $this->entityManager->getCrud()->getSchemaId();
      $result = $this->findOneBy([$getId => $id]);
      if ($result !== null && count($result) > 0) {
        $params = (!empty($fields)) ? array_merge([$getId => $id], $fields) : $fields;
        $update = $this->entityManager->getCrud()->update($params, [$getId => $id]);
        if ($update) {
          return true;
        }
      }
      return false;
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  /**
   * @inheritDoc
   * 
   * @return array
   */
  public function findWithSearchAndPaging(object $request, array $args = []): array
  {
    return [];
  }


  /**
   * @inheritDoc
   * 
   * @return self
   */
  public function findAndReturn(int $id, array $selectors = []): self
  {
    return $this;
  }
}