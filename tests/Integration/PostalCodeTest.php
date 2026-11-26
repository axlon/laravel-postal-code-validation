<?php

namespace Tests\Integration;

use Axlon\PostalCodeValidation\Extensions\PostalCode;
use Axlon\PostalCodeValidation\PostalCodeValidator;
use Axlon\PostalCodeValidation\Support\PostalCodeExamples;
use Axlon\PostalCodeValidation\ValidationServiceProvider;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(PostalCode::class)]
#[CoversClass(ValidationServiceProvider::class)]
#[UsesClass(PostalCodeValidator::class)]
#[UsesClass(PostalCodeExamples::class)]
class PostalCodeTest extends TestCase
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

    /**
     * Test if the 'postal_code' rule fails on invalid countries.
     *
     * @return void
     */
    public function testValidationFailsInvalidCountry(): void
    {
        $validator = Validator::make(
            ['postal_code' => '1234 AB'],
            ['postal_code' => 'postal_code:not-a-country'],
        );

        self::assertFalse($validator->passes());
        self::assertContains('validation.postal_code', $validator->errors()->all());
    }

    /**
     * Test if the 'postal_code' rule fails invalid input.
     *
     * @return void
     */
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
     * Test if the 'postal_code' rule fails null input.
     *
     * @return void
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

    /**
     * Test if the 'postal_code' rule passes valid input.
     *
     * @return void
     */
    public function testValidationPassesValidPostalCode(): void
    {
        $validator = Validator::make(
            ['postal_code' => '1234 AB'],
            ['postal_code' => 'postal_code:NL'],
        );

        self::assertTrue($validator->passes());
    }

    /**
     * Test if an exception is thrown when calling the 'postal_code' rule without arguments.
     *
     * @return void
     */
    public function testValidationThrowsWithoutParameters(): void
    {
        $validator = Validator::make(
            ['postal_code' => '1234 AB'],
            ['postal_code' => 'postal_code'],
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/\AValidation rule postal_code requires at least 1 parameter\.\z/');

        $validator->validate();
    }
}
