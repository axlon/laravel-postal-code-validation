<?php

namespace Tests;

use Axlon\PostalCodeValidation\Contracts\PostalCodeValidator as PostalCodeValidatorContract;
use Axlon\PostalCodeValidation\PostalCodeServiceProvider;
use Axlon\PostalCodeValidation\PostalCodeValidator;
use Axlon\PostalCodeValidation\Rules\PostalCode;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(PostalCodeServiceProvider::class)]
#[UsesClass(PostalCode::class)]
#[UsesClass(PostalCodeValidator::class)]
final class PostalCodeServiceProviderTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            PostalCodeServiceProvider::class,
        ];
    }

    public function testItInjectsPostalCodeValidatorIntoRule(): void
    {
        $resolved = null;

        $this->app?->resolving(PostalCodeValidatorContract::class, static function (mixed $validator) use (&$resolved) {
            $resolved = $validator;
        });

        PostalCode::of('NL');

        self::assertInstanceOf(PostalCodeValidator::class, $resolved);
    }

    public function testItValidatesPostalCodes(): void
    {
        $engine = $this->createMock(PostalCodeValidatorContract::class);
        $this->app?->instance(PostalCodeValidatorContract::class, $engine);

        $validator = Validator::make(
            ['value' => '1234 AB'],
            ['value' => 'postal_code:NL'],
        );

        $engine->expects(self::once())->method('isValid')->with('1234 AB', 'NL')->willReturn(true);

        self::assertTrue($validator->passes());
    }

    public function testItValidatesPostalCodeArrays(): void
    {
        $engine = $this->createMock(PostalCodeValidatorContract::class);
        $this->app?->instance(PostalCodeValidatorContract::class, $engine);

        $validator = Validator::make(
            ['values' => ['1234 AB', '1234'], 'other' => ['NL', 'BE']],
            ['values.*' => 'postal_code:other.*'],
        );

        $engine->expects(self::exactly(2))->method('isValid')->willReturnMap([
            ['1234 AB', 'NL', true],
            ['1234', 'BE', true],
        ]);

        self::assertTrue($validator->passes());
    }

    public function testItReplacesAreaCodesInArrayValidationErrors(): void
    {
        $engine = self::createStub(PostalCodeValidatorContract::class);
        $engine->method('isValid')->willReturn(false);
        $this->app?->instance(PostalCodeValidatorContract::class, $engine);

        $validator = Validator::make(
            ['values' => ['invalid', 'invalid'], 'other' => ['NL', 'BE']],
            ['values.*' => 'postal_code:other.*'],
            ['values.*.postal_code' => 'Enter a valid postal code for :areas.'],
        );

        self::assertFalse($validator->passes());
        self::assertSame([
            'values.0' => ['Enter a valid postal code for NL.'],
            'values.1' => ['Enter a valid postal code for BE.'],
        ], $validator->errors()->getMessages());
    }

    public function testItThrowsWhenValidatingPostalCodesWithoutParameters(): void
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
