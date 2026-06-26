<?php

namespace LaraCore\Tests\Unit\Helpers;

use LaraCore\Framework\Helpers\Hash;
use LaraCore\Tests\TestCase;

class HashTest extends TestCase
{
    public function testMakeReturnsNonEmptyString(): void
    {
        $hash = Hash::make('secret');
        $this->assertIsString($hash);
        $this->assertNotEmpty($hash);
    }

    public function testMakeProducesUniqueHashesForSameInput(): void
    {
        $this->assertNotSame(Hash::make('secret'), Hash::make('secret'));
    }

    public function testVerifyReturnsTrueForCorrectPassword(): void
    {
        $hash = Hash::make('correct');
        $this->assertTrue(Hash::verify('correct', $hash));
    }

    public function testVerifyReturnsFalseForWrongPassword(): void
    {
        $hash = Hash::make('correct');
        $this->assertFalse(Hash::verify('wrong', $hash));
    }

    public function testVerifyReturnsFalseForEmptyPassword(): void
    {
        $hash = Hash::make('secret');
        $this->assertFalse(Hash::verify('', $hash));
    }
}
