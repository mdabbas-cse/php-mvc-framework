<?php

namespace LaraCore\Framework\Db\Factories;

class Factory
{
  public function make($model, $data)
  {
    $model = new $model();
    $model->loadData($data);
    $model->save();
    return $model;
  }
}