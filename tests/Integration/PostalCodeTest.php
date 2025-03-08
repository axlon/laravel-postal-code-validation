<?php

declare(strict_types=1);

namespace Tests\Integration;

use InvalidArgumentException;

final class PostalCodeTest extends TestCase
{
    /**
     * Test if the 'postal_code' rule fails on invalid countries.
     *
     * @return void
     */
    public function testValidationFailsInvalidCountry(): void
    {
        $validator = $this->app->make('validator')->make(
            ['postal_code' => '1234 AB'],
            ['postal_code' => 'postal_code:not-a-country'],
        );

        $this->assertFalse($validator->passes());
        $this->assertContains('validation.postal_code', $validator->errors()->all());
    }

    /**
     * Test if the 'postal_code' rule fails invalid input.
     *
     * @return void
     */
    public function testValidationFailsInvalidPostalCode(): void
    {
        $validator = $this->app->make('validator')->make(
            ['postal_code' => 'not-a-postal-code'],
            ['postal_code' => 'postal_code:NL'],
        );

        $this->assertFalse($validator->passes());
        $this->assertContains('validation.postal_code', $validator->errors()->all());
    }

    /**
     * Test if the 'postal_code' rule fails null input.
     *
     * @return void
     * @link https://github.com/axlon/laravel-postal-code-validation/issues/23
     */
    public function testValidationFailsNullPostalCode(): void
    {
        $validator = $this->app->make('validator')->make(
            ['postal_code' => null],
            ['postal_code' => 'postal_code:DE'],
        );

        $this->assertFalse($validator->passes());
        $this->assertContains('validation.postal_code', $validator->errors()->all());
    }

    /**
     * Test if the 'postal_code' rule passes valid input.
     *
     * @return void
     */
    public function testValidationPassesValidPostalCode(): void
    {
        $validator = $this->app->make('validator')->make(
            ['postal_code' => '1234 AB'],
            ['postal_code' => 'postal_code:NL'],
        );

        $this->assertTrue($validator->passes());
        $this->assertEmpty($validator->errors()->all());
    }

    /**
     * Test if an exception is thrown when calling the 'postal_code' rule without arguments.
     *
     * @return void
     */
    public function testValidationThrowsWithoutParameters(): void
    {
        $validator = $this->app->make('validator')->make(
            ['postal_code' => '1234 AB'],
            ['postal_code' => 'postal_code'],
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Validation rule postal_code requires at least 1 parameter.');

        $validator->validate();
    }
}
