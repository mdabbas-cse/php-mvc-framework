<?php

namespace LaraCore\Tests;

use LaraCore\Framework\Db\Connection;
use PDO;

abstract class DatabaseTestCase extends TestCase
{
    protected PDO $pdo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

        Connection::setInstance($this->pdo);

        $this->createSchema();
    }

    protected function tearDown(): void
    {
        Connection::setInstance(null);
        parent::tearDown();
    }

    protected function createSchema(): void
    {
        // Override in subclasses to create tables.
    }
}
