<?php

namespace LaraCore\Framework\Factories;

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