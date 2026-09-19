<?php

declare(strict_types=1);

namespace Tests\Support;

use Axlon\PostalCodeValidation\PostalCodeServiceProvider;
use Axlon\PostalCodeValidation\PostalCodeValidator;
use Axlon\PostalCodeValidation\Rules\PostalCode;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
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

    public function testItBindsPostalCodeValidator(): void
    {
        $validator = App::make(PostalCodeValidator::class);

        self::assertSame($validator, App::make(PostalCodeValidator::class));
    }

    public function testItRegistersPostalCodeRule(): void
    {
        App::instance(PostalCodeValidator::class, new PostalCodeValidator([
            'NL' => '/^\d{4} ?[A-Z]{2}$/i',
        ]));

        $validator = Validator::make(
            ['value' => '1234 AB', 'country' => 'NL'],
            ['value' => 'postal_code:NL'],
        );

        self::assertTrue($validator->passes());
    }

    public function testItRegistersPostalCodeReplacer(): void
    {
        App::instance(PostalCodeValidator::class, new PostalCodeValidator([
            'NL' => '/^\d{4} ?[A-Z]{2}$/i',
        ]));

        Lang::addLines([
            'validation.postal_code' => 'The :attribute must be a valid :regions postal code.',
        ], locale: 'en');

        $validator = Validator::make(
            [
                'values' => [
                    ['postal_code' => '1234 AB', 'country' => 'NL'],
                    ['postal_code' => '1234 AB', 'country' => 'BE'],
                ],
            ],
            [
                'values.*.postal_code' => 'postal_code:values.*.country',
            ],
        );

        self::assertFalse($validator->passes());
        self::assertSame(
            ['The values.1.postal_code must be a valid BE postal code.'],
            $validator->messages()->all(),
        );
    }
}
