<?php

namespace LaraCore\Database\Factories;

use LaraCore\App\Models\Users;
use LaraCore\Framework\Db\Factories\Factory;

class UserFactory extends Factory
{
  public function definition()
  {
    $quizJson = file_get_contents(base_path('quiz.json'));
    $quiz = json_decode($quizJson, true);
    return [
      'questions' => json_encode($quiz[2])
    ];
  }

  public function create()
  {
    $data = $this->definition();
    $this->make(Users::class, $data);
  }
  public static function new()
  {
    return new static;
  }

}