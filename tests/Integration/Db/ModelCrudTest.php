<?php

namespace LaraCore\Tests\Integration\Db;

use LaraCore\Tests\DatabaseTestCase;
use LaraCore\Tests\Fixtures\TestUser;

/**
 * @group db
 */
class ModelCrudTest extends DatabaseTestCase
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

    public function testCreatingInsertsRowAndSetsExists(): void
    {
        $user = TestUser::creating(['name' => 'Alice', 'email' => 'alice@example.com']);

        $this->assertInstanceOf(TestUser::class, $user);
        $this->assertTrue($user->exists);
        $this->assertNotNull($user->getAttribute('id'));
    }

    public function testFindReturnsModelById(): void
    {
        TestUser::creating(['name' => 'Bob', 'email' => 'bob@example.com']);
        $bob = TestUser::find(1);

        $this->assertInstanceOf(TestUser::class, $bob);
        $this->assertSame('Bob', $bob->name);
    }

    public function testFindReturnsNullForNonExistentId(): void
    {
        $this->assertNull(TestUser::find(999));
    }

    public function testFindOrFailThrowsForMissingId(): void
    {
        $this->expectException(\RuntimeException::class);
        TestUser::findOrFail(999);
    }

    public function testAllReturnsCollection(): void
    {
        TestUser::creating(['name' => 'Carol', 'email' => 'carol@example.com']);
        TestUser::creating(['name' => 'Dave',  'email' => 'dave@example.com']);
        $all = TestUser::all();

        $this->assertCount(2, $all);
    }

    public function testSavingUpdatesPersisted(): void
    {
        $user = TestUser::creating(['name' => 'Eve', 'email' => 'eve@example.com']);
        $user->name = 'Eve Updated';
        $result = $user->saving();

        $this->assertTrue($result);

        $fresh = TestUser::find($user->getAttribute('id'));
        $this->assertSame('Eve Updated', $fresh->name);
    }

    public function testUpdatedChangesAttributes(): void
    {
        $user = TestUser::creating(['name' => 'Frank', 'email' => 'frank@example.com']);
        $result = $user->updated(['name' => 'Frank Updated']);

        $this->assertTrue($result);

        $fresh = TestUser::find($user->getAttribute('id'));
        $this->assertSame('Frank Updated', $fresh->name);
    }

    public function testDeleteRemovesRow(): void
    {
        $user = TestUser::creating(['name' => 'Grace', 'email' => 'grace@example.com']);
        $id   = $user->getAttribute('id');
        $user->delete();

        $this->assertNull(TestUser::find($id));
    }

    public function testFirstOrCreateCreatesWhenNotFound(): void
    {
        $user = TestUser::firstOrCreate(
            ['email' => 'heidi@example.com'],
            ['name'  => 'Heidi']
        );

        $this->assertInstanceOf(TestUser::class, $user);
        $this->assertSame('Heidi', $user->name);

        $stmt = $this->pdo->query("SELECT COUNT(*) FROM test_users");
        $this->assertSame(1, (int) $stmt->fetchColumn());
    }

    public function testFirstOrCreateReturnsExistingWhenFound(): void
    {
        TestUser::creating(['name' => 'Ivan', 'email' => 'ivan@example.com']);

        $user = TestUser::firstOrCreate(
            ['email' => 'ivan@example.com'],
            ['name'  => 'Ivan Duplicate']
        );

        $this->assertSame('Ivan', $user->name);

        $stmt = $this->pdo->query("SELECT COUNT(*) FROM test_users");
        $this->assertSame(1, (int) $stmt->fetchColumn());
    }

    public function testCountStaticReturnsInt(): void
    {
        TestUser::creating(['name' => 'Judy', 'email' => 'judy@example.com']);
        TestUser::creating(['name' => 'Karl', 'email' => 'karl@example.com']);
        $this->assertSame(2, TestUser::count());
    }

    public function testExistsStaticReturnsTrueWhenTableHasRows(): void
    {
        TestUser::creating(['name' => 'Leo', 'email' => 'leo@example.com']);
        $this->assertTrue(TestUser::exists());
    }

    public function testFreshReloadsFromDatabase(): void
    {
        $user = TestUser::creating(['name' => 'Mia', 'email' => 'mia@example.com']);
        // Modify in-memory only (no save)
        $user->setAttribute('name', 'Dirty');

        $fresh = $user->fresh();
        $this->assertSame('Mia', $fresh->name);
        $this->assertSame('Dirty', $user->name); // original instance unchanged
    }
}
