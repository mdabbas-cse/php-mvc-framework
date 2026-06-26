<?php

namespace LaraCore\Tests\Unit\Db\Migrations;

use LaraCore\Framework\Db\Migrations\Blueprint;
use LaraCore\Tests\TestCase;

class BlueprintTest extends TestCase
{
    private function blueprint(): Blueprint
    {
        return new Blueprint();
    }

    public function testIdGeneratesAutoIncrementPrimaryKey(): void
    {
        $b = $this->blueprint();
        $b->id();
        $columns = $b->getColumns();
        $this->assertStringContainsStringIgnoringCase('AUTO_INCREMENT', implode('', $columns));
        $this->assertStringContainsStringIgnoringCase('PRIMARY KEY', implode('', $columns));
    }

    public function testStringColumn(): void
    {
        $b = $this->blueprint();
        $b->string('email', 191);
        $col = implode('', $b->getColumns());
        $this->assertStringContainsString('email', $col);
        $this->assertStringContainsStringIgnoringCase('VARCHAR', $col);
        $this->assertStringContainsString('191', $col);
    }

    public function testIntegerColumn(): void
    {
        $b = $this->blueprint();
        $b->integer('age');
        $col = implode('', $b->getColumns());
        $this->assertStringContainsString('age', $col);
        $this->assertStringContainsStringIgnoringCase('INT', $col);
    }

    public function testTextColumn(): void
    {
        $b = $this->blueprint();
        $b->text('body');
        $col = implode('', $b->getColumns());
        $this->assertStringContainsString('body', $col);
        $this->assertStringContainsStringIgnoringCase('TEXT', $col);
    }

    public function testBooleanColumn(): void
    {
        $b = $this->blueprint();
        $b->boolean('active');
        $col = implode('', $b->getColumns());
        $this->assertStringContainsString('active', $col);
        $this->assertMatchesRegularExpression('/TINYINT|BOOLEAN/i', $col);
    }

    public function testNullableModifier(): void
    {
        $b = $this->blueprint();
        $b->string('nickname')->nullable();
        $col = implode('', $b->getColumns());
        $this->assertStringContainsStringIgnoringCase('NULL', $col);
    }

    public function testDefaultModifier(): void
    {
        $b = $this->blueprint();
        $b->integer('status')->default(0);
        $col = implode('', $b->getColumns());
        $this->assertStringContainsStringIgnoringCase('DEFAULT', $col);
        $this->assertStringContainsString('0', $col);
    }

    public function testTimestampsAddsTwoColumns(): void
    {
        $b = $this->blueprint();
        $b->timestamps();
        $columns = $b->getColumns();
        $all = implode(' ', $columns);
        $this->assertStringContainsString('created_at', $all);
        $this->assertStringContainsString('updated_at', $all);
    }

    public function testMultipleColumnsAccumulate(): void
    {
        $b = $this->blueprint();
        $b->id();
        $b->string('name');
        $b->string('email');
        $this->assertCount(3, $b->getColumns());
    }
}
