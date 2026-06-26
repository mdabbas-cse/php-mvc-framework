<?php

namespace LaraCore\Tests\Unit;

use LaraCore\Framework\Validation;
use LaraCore\Tests\TestCase;

class ValidationTest extends TestCase
{
    private function validate(array $data, array $rules): Validation
    {
        return new Validation($data, $rules);
    }

    public function testRequiredPassesWhenValuePresent(): void
    {
        $v = $this->validate(['name' => 'Alice'], ['name' => ['required']]);
        $this->assertTrue($v->checkValidation());
    }

    public function testRequiredFailsWhenValueEmpty(): void
    {
        $v = $this->validate(['name' => ''], ['name' => ['required']]);
        $this->assertFalse($v->checkValidation());
    }

    public function testRequiredFailsWhenKeyMissing(): void
    {
        $v = $this->validate([], ['name' => ['required']]);
        $this->assertFalse($v->checkValidation());
    }

    public function testEmailPassesForValidEmail(): void
    {
        $v = $this->validate(['email' => 'user@example.com'], ['email' => ['email']]);
        $this->assertTrue($v->checkValidation());
    }

    public function testEmailFailsForInvalidEmail(): void
    {
        $v = $this->validate(['email' => 'not-an-email'], ['email' => ['email']]);
        $this->assertFalse($v->checkValidation());
    }

    public function testMinPassesWhenLengthMet(): void
    {
        $v = $this->validate(
            ['pass' => 'abcdefgh'],
            ['pass' => [['min', 'min' => 6]]]
        );
        $this->assertTrue($v->checkValidation());
    }

    public function testMinFailsWhenTooShort(): void
    {
        $v = $this->validate(
            ['pass' => 'abc'],
            ['pass' => [['min', 'min' => 6]]]
        );
        $this->assertFalse($v->checkValidation());
    }

    public function testMaxPassesWhenLengthWithin(): void
    {
        $v = $this->validate(
            ['bio' => 'Short bio'],
            ['bio' => [['max', 'max' => 100]]]
        );
        $this->assertTrue($v->checkValidation());
    }

    public function testMaxFailsWhenTooLong(): void
    {
        $v = $this->validate(
            ['bio' => str_repeat('x', 101)],
            ['bio' => [['max', 'max' => 100]]]
        );
        $this->assertFalse($v->checkValidation());
    }

    public function testMatchPassesWhenValuesEqual(): void
    {
        $data = ['password' => 'secret', 'confirm' => 'secret'];
        $v = $this->validate(
            $data,
            ['confirm' => [['match', 'match' => 'password']]]
        );
        $this->assertTrue($v->checkValidation());
    }

    public function testMatchFailsWhenValuesDiffer(): void
    {
        $data = ['password' => 'secret', 'confirm' => 'wrong'];
        $v = $this->validate(
            $data,
            ['confirm' => [['match', 'match' => 'password']]]
        );
        $this->assertFalse($v->checkValidation());
    }

    public function testGetErrorsReturnsFalseWhenValid(): void
    {
        $v = $this->validate(['name' => 'Bob'], ['name' => ['required']]);
        $v->checkValidation();
        $this->assertFalse($v->getErrors());
    }

    public function testGetErrorsReturnsArrayWhenInvalid(): void
    {
        $v = $this->validate(['name' => ''], ['name' => ['required']]);
        $v->checkValidation();
        $errors = $v->getErrors();
        $this->assertIsArray($errors);
        $this->assertArrayHasKey('name', $errors);
    }

    public function testMultipleRulesCollectAllErrors(): void
    {
        $v = $this->validate(
            ['email' => ''],
            ['email' => ['required', 'email']]
        );
        $v->checkValidation();
        // At least the required error should be set
        $errors = $v->getErrors();
        $this->assertArrayHasKey('email', $errors);
    }
}
