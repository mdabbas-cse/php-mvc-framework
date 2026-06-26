<?php

namespace LaraCore\Tests\Unit\Db;

use LaraCore\Framework\Db\Connection;
use LaraCore\Framework\Db\QueryBuilder;
use LaraCore\Tests\Fixtures\TestUser;
use LaraCore\Tests\TestCase;
use PDO;

class QueryBuilderSqlTest extends TestCase
{
    private PDO $pdo;

    protected function setUp(): void
    {
        parent::setUp();
        // Inject a real SQLite PDO so QueryBuilder can be instantiated without MySQL
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        Connection::setInstance($this->pdo);
    }

    protected function tearDown(): void
    {
        Connection::setInstance(null);
        parent::tearDown();
    }

    private function qb(): QueryBuilder
    {
        return new QueryBuilder(TestUser::class);
    }

    public function testDefaultSelectAll(): void
    {
        $sql = $this->qb()->toSql();
        $this->assertStringContainsString('SELECT *', $sql);
        $this->assertStringContainsString('`test_users`', $sql);
    }

    public function testSelectSpecificColumns(): void
    {
        $sql = $this->qb()->select('name', 'email')->toSql();
        $this->assertStringContainsString('name, email', $sql);
    }

    public function testDistinctKeyword(): void
    {
        $sql = $this->qb()->distinct()->toSql();
        $this->assertStringContainsString('SELECT DISTINCT', $sql);
    }

    public function testWhereProducesWhereClause(): void
    {
        $sql = $this->qb()->where('name', 'Alice')->toSql();
        $this->assertStringContainsString('WHERE', $sql);
        $this->assertStringContainsString('`name`', $sql);
    }

    public function testWhereWithExplicitOperator(): void
    {
        $sql = $this->qb()->where('age', '>', 18)->toSql();
        $this->assertStringContainsString('`age` > :b0', $sql);
    }

    public function testOrWhereAddsOrClause(): void
    {
        $sql = $this->qb()->where('name', 'Alice')->orWhere('name', 'Bob')->toSql();
        $this->assertStringContainsString('OR', $sql);
    }

    public function testWhereInProducesInClause(): void
    {
        $sql = $this->qb()->whereIn('id', [1, 2, 3])->toSql();
        $this->assertMatchesRegularExpression('/`id` IN \(:b0, :b1, :b2\)/', $sql);
    }

    public function testWhereNotInProducesNotInClause(): void
    {
        $sql = $this->qb()->whereNotIn('id', [1, 2])->toSql();
        $this->assertStringContainsString('NOT IN', $sql);
    }

    public function testWhereNullProducesIsNullClause(): void
    {
        $sql = $this->qb()->whereNull('deleted_at')->toSql();
        $this->assertStringContainsString('`deleted_at` IS NULL', $sql);
    }

    public function testWhereNotNullProducesIsNotNullClause(): void
    {
        $sql = $this->qb()->whereNotNull('deleted_at')->toSql();
        $this->assertStringContainsString('IS NOT NULL', $sql);
    }

    public function testWhereBetweenProducesBetweenClause(): void
    {
        $sql = $this->qb()->whereBetween('age', [18, 65])->toSql();
        $this->assertStringContainsString('BETWEEN', $sql);
        $this->assertStringContainsString(':b0', $sql);
        $this->assertStringContainsString(':b1', $sql);
    }

    public function testOrderByAppendsOrderClause(): void
    {
        $sql = $this->qb()->orderBy('name')->toSql();
        $this->assertStringContainsString('ORDER BY name ASC', $sql);
    }

    public function testOrderByDescending(): void
    {
        $sql = $this->qb()->orderBy('created_at', 'DESC')->toSql();
        $this->assertStringContainsString('created_at DESC', $sql);
    }

    public function testLatestUsesCreatedAtDesc(): void
    {
        $sql = $this->qb()->latest()->toSql();
        $this->assertStringContainsString('created_at DESC', $sql);
    }

    public function testOldestUsesCreatedAtAsc(): void
    {
        $sql = $this->qb()->oldest()->toSql();
        $this->assertStringContainsString('created_at ASC', $sql);
    }

    public function testLimitAppendsLimitClause(): void
    {
        $sql = $this->qb()->limit(10)->toSql();
        $this->assertStringContainsString('LIMIT 10', $sql);
    }

    public function testOffsetAppendsOffsetClause(): void
    {
        $sql = $this->qb()->limit(5)->offset(10)->toSql();
        $this->assertStringContainsString('OFFSET 10', $sql);
    }

    public function testGroupByAppendsGroupClause(): void
    {
        $sql = $this->qb()->groupBy('status')->toSql();
        $this->assertStringContainsString('GROUP BY status', $sql);
    }

    public function testInnerJoinAppendsJoinClause(): void
    {
        $sql = $this->qb()->join('posts', 'test_users.id', '=', 'posts.user_id')->toSql();
        $this->assertStringContainsString('INNER JOIN `posts`', $sql);
        $this->assertStringContainsString('test_users.id = posts.user_id', $sql);
    }

    public function testLeftJoinAppendsLeftJoinClause(): void
    {
        $sql = $this->qb()->leftJoin('orders', 'test_users.id', '=', 'orders.user_id')->toSql();
        $this->assertStringContainsString('LEFT JOIN', $sql);
    }

    public function testWhereRawInjectsSqlDirectly(): void
    {
        $sql = $this->qb()->whereRaw('YEAR(created_at) = 2024')->toSql();
        $this->assertStringContainsString('YEAR(created_at) = 2024', $sql);
    }

    public function testForPageCalculatesLimitOffset(): void
    {
        $sql = $this->qb()->forPage(3, 10)->toSql();
        $this->assertStringContainsString('LIMIT 10', $sql);
        $this->assertStringContainsString('OFFSET 20', $sql);
    }
}
