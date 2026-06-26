<?php

namespace LaraCore\Tests\Unit\Db;

use LaraCore\Tests\Fixtures\TestUser;
use LaraCore\Tests\TestCase;

class ModelTest extends TestCase
{
    public function testFillSetsOnlyFillableAttributes(): void
    {
        $user = new TestUser();
        $user->fill(['name' => 'Alice', 'email' => 'a@b.com', 'secret' => 'x']);

        $this->assertSame('Alice',  $user->getAttribute('name'));
        $this->assertSame('a@b.com', $user->getAttribute('email'));
        $this->assertNull($user->getAttribute('secret'));
    }

    public function testSetAttributeAndGetAttribute(): void
    {
        $user = new TestUser();
        $user->setAttribute('name', 'Bob');
        $this->assertSame('Bob', $user->getAttribute('name'));
    }

    public function testMagicGetSetRoutesThroughAttributeBag(): void
    {
        $user = new TestUser();
        $user->name = 'Carol';
        $this->assertSame('Carol', $user->name);
    }

    public function testIsDirtyReturnsTrueAfterChange(): void
    {
        $user       = new TestUser();
        $reflection = new \ReflectionClass($user);

        // Seed both attributes and original to the same persisted state
        $attrs = $reflection->getProperty('attributes');
        $attrs->setAccessible(true);
        $attrs->setValue($user, ['name' => 'Dave', 'email' => 'dave@example.com']);

        $orig = $reflection->getProperty('original');
        $orig->setAccessible(true);
        $orig->setValue($user, ['name' => 'Dave', 'email' => 'dave@example.com']);

        // Change only name
        $user->name = 'Eve';
        $this->assertTrue($user->isDirty('name'));
        $this->assertFalse($user->isDirty('email'));
    }

    public function testGetDirtyReturnsChangedAttributes(): void
    {
        $user = new TestUser();

        $reflection = new \ReflectionClass($user);
        $attrs = $reflection->getProperty('attributes');
        $attrs->setAccessible(true);
        $attrs->setValue($user, ['name' => 'Old', 'email' => 'e@e.com']);

        $orig = $reflection->getProperty('original');
        $orig->setAccessible(true);
        $orig->setValue($user, ['name' => 'Old', 'email' => 'e@e.com']);

        $user->name = 'New';
        $dirty = $user->getDirty();
        $this->assertArrayHasKey('name', $dirty);
        $this->assertArrayNotHasKey('email', $dirty);
    }

    public function testCastIntCastsValue(): void
    {
        $user = new TestUser();
        $user->setAttribute('age', '25');
        $this->assertSame(25, $user->getAttribute('age'));
    }

    public function testCastBoolCastsValue(): void
    {
        $user = new TestUser();
        $user->setAttribute('active', 1);
        $this->assertSame(true, $user->getAttribute('active'));
    }

    public function testToArrayExcludesHiddenFields(): void
    {
        // TestUser has no hidden fields; use a quick anonymous subclass
        $model = new class extends TestUser {
            protected $hidden = ['email'];
        };
        $model->fill(['name' => 'Frank', 'email' => 'f@f.com']);
        $arr = $model->toArray();

        $this->assertArrayHasKey('name', $arr);
        $this->assertArrayNotHasKey('email', $arr);
    }

    public function testToJsonEncodesAttributes(): void
    {
        $user = new TestUser();
        $user->fill(['name' => 'Grace', 'email' => 'g@g.com']);
        $json = json_decode($user->toJson(), true);

        $this->assertSame('Grace',   $json['name']);
        $this->assertSame('g@g.com', $json['email']);
    }

    public function testNewFromBuilderHydratesModel(): void
    {
        $user = new TestUser();
        $row  = ['id' => 1, 'name' => 'Henry', 'email' => 'h@h.com', 'age' => '30', 'active' => '1'];
        $hydrated = $user->newFromBuilder($row);

        $this->assertInstanceOf(TestUser::class, $hydrated);
        $this->assertTrue($hydrated->exists);
        $this->assertSame('Henry', $hydrated->name);
        $this->assertSame(30, $hydrated->age);   // cast to int
        $this->assertSame(true, $hydrated->active); // cast to bool
    }

    public function testExistsIsFalseOnNewModel(): void
    {
        $user = new TestUser();
        $this->assertFalse($user->exists);
    }

    public function testGetTableReturnsStaticTable(): void
    {
        $this->assertSame('test_users', TestUser::getTable());
    }
}
