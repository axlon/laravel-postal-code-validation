<?php

namespace Axlon\PostalCodeValidation\Tests\Integration;

use InvalidArgumentException;

class PostalCodeForTest extends TestCase
{
    /**
     * Test if the 'postal_code_for' rule fails on invalid countries.
     *
     * @return void
     */
    public function testValidationFailsInvalidCountry(): void
    {
        $validator = $this->app->make('validator')->make(
            ['postal_code' => '1234 AB', 'country' => 'not-a-country'],
            ['postal_code' => 'postal_code_for:country']
        );

        $this->assertFalse($validator->passes());
        $this->assertContains('validation.postal_code_for', $validator->errors()->all());
    }

    /**
     * Test if the 'postal_code_for' rule fails invalid input.
     *
     * @return void
     */
    public function testValidationFailsInvalidPostalCode(): void
    {
        $validator = $this->app->make('validator')->make(
            ['postal_code' => 'not-a-postal-code', 'country' => 'NL'],
            ['postal_code' => 'postal_code_for:country']
        );

        $this->assertFalse($validator->passes());
        $this->assertContains('validation.postal_code_for', $validator->errors()->all());
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
            ['postal_code' => null, 'country' => 'DE'],
            ['postal_code' => 'postal_code_for:country']
        );

        $this->assertFalse($validator->passes());
        $this->assertContains('validation.postal_code_for', $validator->errors()->all());
    }

    public function testValidationPassesIfAllFieldsAreMissing(): void
    {
        $validator = $this->app->make('validator')->make(
            ['postal_code' => '1234 AB'],
            ['postal_code' => 'postal_code_for:country']
        );

        $this->assertTrue($validator->passes());
        $this->assertEmpty($validator->errors()->all());
    }

    /**
     * Test if the 'postal_code_for' rule ignores references that aren't present.
     *
     * @return void
     */
    public function testValidationIgnoresMissingFields(): void
    {
        $validator = $this->app->make('validator')->make(
            ['postal_code' => '1234 AB', 'empty' => '', 'null' => null, 'country' => 'NL'],
            ['postal_code' => 'postal_code_for:empty,missing,null,country']
        );

        $this->assertTrue($validator->passes());
        $this->assertEmpty($validator->errors()->all());
    }

    /**
     * Test if the 'postal_code_for' rule passes valid input.
     *
     * @return void
     */
    public function testValidationPassesValidPostalCode(): void
    {
        $validator = $this->app->make('validator')->make(
            ['postal_code' => '1234 AB', 'country' => 'NL'],
            ['postal_code' => 'postal_code_for:country']
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
            ['postal_code' => 'postal_code_for']
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Validation rule postal_code_for requires at least 1 parameter.');

        $validator->validate();
    }
}
