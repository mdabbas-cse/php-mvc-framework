<?php

namespace MVC\APP\Models\Todos;

use MVC\Framework\Model;

class Todo extends Model
{
  protected $table = 'todos';
  protected $fillable = ['title', 'description', 'completed'];

  public $description;
}
