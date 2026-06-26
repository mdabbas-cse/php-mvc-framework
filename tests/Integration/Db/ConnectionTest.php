<?php

namespace LaraCore\Tests\Integration\Db;

use LaraCore\Framework\Db\Connection;
use LaraCore\Tests\TestCase;
use PDO;

/**
 * @group db
 */
class ConnectionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Connection::setInstance(null); // ensure we start fresh
    }

    protected function tearDown(): void
    {
        Connection::setInstance(null);
        parent::tearDown();
    }

    public function testSetInstanceAndGetInstanceReturnSamePdo(): void
    {
        $pdo = new PDO('sqlite::memory:');
        Connection::setInstance($pdo);
        $this->assertSame($pdo, Connection::getInstance());
    }

    public function testGetInstanceReturnsSingletonOnRepeatCalls(): void
    {
        $pdo = new PDO('sqlite::memory:');
        Connection::setInstance($pdo);
        $a = Connection::getInstance();
        $b = Connection::getInstance();
        $this->assertSame($a, $b);
    }

    public function testSetInstanceNullResetsState(): void
    {
        $pdo = new PDO('sqlite::memory:');
        Connection::setInstance($pdo);
        Connection::setInstance(null);

        // After reset, getInstance() would try to connect to real DB; just assert
        // that the old $pdo is no longer returned by checking the property.
        $ref = new \ReflectionProperty(Connection::class, 'instance');
        $ref->setAccessible(true);
        $this->assertNull($ref->getValue(null));
    }
}
