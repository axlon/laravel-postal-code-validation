<?php

declare(strict_types=1);

namespace Tests\Integration;

use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class PostalCodeTest extends TestCase
{
    public static function provideUnsupportedValueTypes(): array
    {
        return [
            [['1234 AB']],
            [1234],
            [12.34],
            [true],
        ];
    }

    public function testValidationFailsInvalidCountry(): void
    {
        $validator = Validator::make(
            ['postal_code' => '1234 AB'],
            ['postal_code' => 'postal_code:not-a-country'],
        );

        self::assertFalse($validator->passes());
        self::assertContains('validation.postal_code', $validator->errors()->all());
    }

    public function testValidationFailsInvalidPostalCode(): void
    {
        $validator = Validator::make(
            ['postal_code' => 'not-a-postal-code'],
            ['postal_code' => 'postal_code:NL'],
        );

        self::assertFalse($validator->passes());
        self::assertContains('validation.postal_code', $validator->errors()->all());
    }

    #[DataProvider('provideUnsupportedValueTypes')]
    public function testValidationFailsInvalidPostalCodeType(mixed $value): void
    {
        $validator = Validator::make(
            ['postal_code' => $value],
            ['postal_code' => 'postal_code:NL'],
        );

        self::assertFalse($validator->passes());
        self::assertContains('validation.postal_code', $validator->errors()->all());
    }

    /**
     * @link https://github.com/axlon/laravel-postal-code-validation/issues/23
     */
    public function testValidationFailsNullPostalCode(): void
    {
        $validator = Validator::make(
            ['postal_code' => null],
            ['postal_code' => 'postal_code:DE'],
        );

        self::assertFalse($validator->passes());
        self::assertContains('validation.postal_code', $validator->errors()->all());
    }

    public function testValidationPassesValidPostalCode(): void
    {
        $validator = Validator::make(
            ['postal_code' => '1234 AB'],
            ['postal_code' => 'postal_code:NL'],
        );

        self::assertTrue($validator->passes());
        self::assertEmpty($validator->errors()->all());
    }

    public function testValidationThrowsWithoutParameters(): void
    {
        $validator = Validator::make(
            ['postal_code' => '1234 AB'],
            ['postal_code' => 'postal_code'],
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Validation rule postal_code requires at least 1 parameter.');

        $validator->validate();
    }
}
