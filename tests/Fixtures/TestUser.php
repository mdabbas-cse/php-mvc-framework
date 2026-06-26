<?php

namespace LaraCore\Tests\Fixtures;

use LaraCore\Framework\Db\Model;

class TestUser extends Model
{
    protected static $table      = 'test_users';
    protected static $primaryKey = 'id';
    protected static $timestamps = false;

    protected $fillable = ['name', 'email', 'age', 'active'];
    protected $casts    = ['age' => 'int', 'active' => 'bool'];
}
