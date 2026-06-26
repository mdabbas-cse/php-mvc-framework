<?php

namespace LaraCore\Tests\Integration\Db;

use LaraCore\Tests\DatabaseTestCase;
use LaraCore\Tests\Fixtures\TestUser;

/**
 * @group db
 */
class QueryBuilderIntegrationTest extends DatabaseTestCase
{
    protected function createSchema(): void
    {
        $this->pdo->exec("CREATE TABLE test_users (
            id     INTEGER PRIMARY KEY AUTOINCREMENT,
            name   TEXT    NOT NULL,
            email  TEXT    NOT NULL,
            age    INTEGER DEFAULT 0,
            active INTEGER DEFAULT 1
        )");
    }

    private function seed(): void
    {
        $this->pdo->exec("INSERT INTO test_users (name, email, age, active) VALUES
            ('Alice', 'alice@example.com', 30, 1),
            ('Bob',   'bob@example.com',   25, 0),
            ('Carol', 'carol@example.com', 35, 1)
        ");
    }

    public function testInsertRecordAddsRow(): void
    {
        $qb = TestUser::where('id', '>', 0); // returns QueryBuilder
        $qb->insertRecord(['name' => 'Dave', 'email' => 'dave@example.com', 'age' => 28, 'active' => 1]);

        $stmt = $this->pdo->query("SELECT COUNT(*) FROM test_users");
        $this->assertSame(1, (int) $stmt->fetchColumn());
    }

    public function testGetReturnsCollection(): void
    {
        $this->seed();
        $users = TestUser::where('active', 1)->get();
        $this->assertCount(2, $users);
    }

    public function testFirstReturnsOneModel(): void
    {
        $this->seed();
        $user = TestUser::where('name', 'Alice')->first();
        $this->assertInstanceOf(TestUser::class, $user);
        $this->assertSame('Alice', $user->name);
    }

    public function testFirstReturnsNullWhenNoMatch(): void
    {
        $this->seed();
        $user = TestUser::where('name', 'Nobody')->first();
        $this->assertNull($user);
    }

    public function testWhereInFiltersRows(): void
    {
        $this->seed();
        $users = TestUser::whereIn('name', ['Alice', 'Carol'])->get();
        $this->assertCount(2, $users);
    }

    public function testOrderByReturnsInOrder(): void
    {
        $this->seed();
        $users = TestUser::where('id', '>', 0)->orderBy('age', 'ASC')->get();
        $this->assertSame('Bob', $users->first()->name);
    }

    public function testLimitReducesResults(): void
    {
        $this->seed();
        $users = TestUser::where('id', '>', 0)->limit(2)->get();
        $this->assertCount(2, $users);
    }

    public function testCountAggregateReturnsInt(): void
    {
        $this->seed();
        $count = TestUser::where('active', 1)->count();
        $this->assertSame(2, $count);
    }

    public function testUpdateRecordsModifiesRows(): void
    {
        $this->seed();
        $affected = TestUser::where('name', 'Bob')->updateRecords(['active' => 1]);
        $this->assertGreaterThan(0, $affected);

        $bob = TestUser::where('name', 'Bob')->first();
        $this->assertSame(1, (int) $bob->active);
    }

    public function testDeleteRecordsRemovesRows(): void
    {
        $this->seed();
        TestUser::where('name', 'Bob')->deleteRecords();

        $stmt = $this->pdo->query("SELECT COUNT(*) FROM test_users");
        $this->assertSame(2, (int) $stmt->fetchColumn());
    }

    public function testExistsReturnsTrueWhenFound(): void
    {
        $this->seed();
        $this->assertTrue(TestUser::where('name', 'Alice')->exists());
    }

    public function testDoesntExistReturnsTrueWhenNotFound(): void
    {
        $this->seed();
        $this->assertTrue(TestUser::where('name', 'Ghost')->doesntExist());
    }

    public function testPaginateReturnsCorrectStructure(): void
    {
        $this->seed();
        $page = TestUser::where('id', '>', 0)->paginate(2, 1);

        $this->assertArrayHasKey('data',         $page);
        $this->assertArrayHasKey('total',        $page);
        $this->assertArrayHasKey('per_page',     $page);
        $this->assertArrayHasKey('current_page', $page);
        $this->assertSame(2, count($page['data']->all()));
        $this->assertSame(3, $page['total']);
    }
}
