<?php

declare(strict_types=1);

namespace Tests\Integration;

use InvalidArgumentException;
use Tests\TestCase;

class PostalCodeWithTest extends TestCase
{
    public function testValidationFailsInvalidCountry(): void
    {
        $validator = $this->app->make('validator')->make(
            ['postal_code' => '1234 AB', 'country' => 'not-a-country'],
            ['postal_code' => 'postal_code_with:country'],
        );

        self::assertFalse($validator->passes());
        self::assertContains('validation.postal_code_with', $validator->errors()->all());
    }

    public function testValidationFailsInvalidPostalCode(): void
    {
        $validator = $this->app->make('validator')->make(
            ['postal_code' => 'not-a-postal-code', 'country' => 'NL'],
            ['postal_code' => 'postal_code_with:country'],
        );

        self::assertFalse($validator->passes());
        self::assertContains('validation.postal_code_with', $validator->errors()->all());
    }

    public function testValidationFailsInvalidPostalCodeInArray(): void
    {
        $validator = $this->app->make('validator')->make(
            ['postal_codes' => ['not-a-postal-code'], 'countries' => ['NL']],
            ['postal_codes.*' => 'postal_code_with:countries.*'],
        );

        self::assertFalse($validator->passes());
        self::assertContains('validation.postal_code_with', $validator->errors()->all());
    }

    /**
     * @link https://github.com/axlon/laravel-postal-code-validation/issues/23
     */
    public function testValidationFailsNullPostalCode(): void
    {
        $validator = $this->app->make('validator')->make(
            ['postal_code' => null, 'country' => 'DE'],
            ['postal_code' => 'postal_code_with:country'],
        );

        self::assertFalse($validator->passes());
        self::assertContains('validation.postal_code_with', $validator->errors()->all());
    }

    public function testValidationPassesIfAllFieldsAreMissing(): void
    {
        $validator = $this->app->make('validator')->make(
            ['postal_code' => '1234 AB'],
            ['postal_code' => 'postal_code_with:country'],
        );

        self::assertTrue($validator->passes());
        self::assertEmpty($validator->errors()->all());
    }

    public function testValidationIgnoresMissingFields(): void
    {
        $validator = $this->app->make('validator')->make(
            ['postal_code' => '1234 AB', 'empty' => '', 'null' => null, 'country' => 'NL'],
            ['postal_code' => 'postal_code_with:empty,missing,null,country'],
        );

        self::assertTrue($validator->passes());
        self::assertEmpty($validator->errors()->all());
    }

    public function testValidationIgnoresMissingFieldsFailing(): void
    {
        $validator = $this->app->make('validator')->make(
            ['postal_code' => '1234 AB', 'empty' => '', 'null' => null, 'country' => 'BE'],
            ['postal_code' => 'postal_code_with:empty,missing,null,country'],
        );

        self::assertFalse($validator->passes());
        self::assertContains('validation.postal_code_with', $validator->errors()->all());
    }

    public function testValidationPassesValidPostalCode(): void
    {
        $validator = $this->app->make('validator')->make(
            ['postal_code' => '1234 AB', 'country' => 'NL'],
            ['postal_code' => 'postal_code_with:country'],
        );

        self::assertTrue($validator->passes());
        self::assertEmpty($validator->errors()->all());
    }

    public function testValidationPassesValidPostalCodeInArray(): void
    {
        $validator = $this->app->make('validator')->make(
            ['postal_codes' => ['1234 AB'], 'countries' => ['NL']],
            ['postal_codes.*' => 'postal_code_with:countries.*'],
        );

        self::assertTrue($validator->passes());
        self::assertEmpty($validator->errors()->all());
    }

    public function testValidationThrowsWithoutParameters(): void
    {
        $validator = $this->app->make('validator')->make(
            ['postal_code' => '1234 AB'],
            ['postal_code' => 'postal_code_with'],
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Validation rule postal_code_with requires at least 1 parameter.');

        $validator->validate();
    }
}
