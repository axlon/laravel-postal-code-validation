<?php

declare(strict_types=1);

namespace Tests\Integration;

use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Tests\TestCase;

class PostalCodeForTest extends TestCase
{
    public function testValidationFailsInvalidCountry(): void
    {
        $validator = Validator::make(
            ['postal_code' => '1234 AB', 'country' => 'not-a-country'],
            ['postal_code' => 'postal_code_for:country'],
        );

        self::assertFalse($validator->passes());
        self::assertContains('validation.postal_code_for', $validator->errors()->all());
    }

    public function testValidationFailsInvalidPostalCode(): void
    {
        $validator = Validator::make(
            ['postal_code' => 'not-a-postal-code', 'country' => 'NL'],
            ['postal_code' => 'postal_code_for:country'],
        );

        self::assertFalse($validator->passes());
        self::assertContains('validation.postal_code_for', $validator->errors()->all());
    }

    /**
     * @link https://github.com/axlon/laravel-postal-code-validation/issues/23
     */
    public function testValidationFailsNullPostalCode(): void
    {
        $validator = Validator::make(
            ['postal_code' => null, 'country' => 'DE'],
            ['postal_code' => 'postal_code_for:country'],
        );

        self::assertFalse($validator->passes());
        self::assertContains('validation.postal_code_for', $validator->errors()->all());
    }

    public function testValidationPassesIfAllFieldsAreMissing(): void
    {
        $validator = Validator::make(
            ['postal_code' => '1234 AB'],
            ['postal_code' => 'postal_code_for:country'],
        );

        self::assertTrue($validator->passes());
        self::assertEmpty($validator->errors()->all());
    }

    public function testValidationIgnoresMissingFields(): void
    {
        $validator = Validator::make(
            ['postal_code' => '1234 AB', 'empty' => '', 'null' => null, 'country' => 'NL'],
            ['postal_code' => 'postal_code_for:empty,missing,null,country'],
        );

        self::assertTrue($validator->passes());
        self::assertEmpty($validator->errors()->all());
    }

    public function testValidationPassesValidPostalCode(): void
    {
        $validator = Validator::make(
            ['postal_code' => '1234 AB', 'country' => 'NL'],
            ['postal_code' => 'postal_code_for:country'],
        );

        self::assertTrue($validator->passes());
        self::assertEmpty($validator->errors()->all());
    }

    public function testValidationThrowsWithoutParameters(): void
    {
        $validator = Validator::make(
            ['postal_code' => '1234 AB'],
            ['postal_code' => 'postal_code_for'],
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Validation rule postal_code_with requires at least 1 parameter.');

        $validator->validate();
    }
}
