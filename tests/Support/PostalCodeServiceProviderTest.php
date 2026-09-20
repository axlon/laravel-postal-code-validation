<?php

declare(strict_types=1);

namespace Tests\Support;

use Axlon\PostalCodeValidation\Constraints\ConstraintRegistry;
use Axlon\PostalCodeValidation\PostalCodeServiceProvider;
use Axlon\PostalCodeValidation\PostalCodeValidator;
use Axlon\PostalCodeValidation\Rules\PostalCode;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use Tests\Fakes\FakeConstraint;
use Tests\Fakes\FakeConstraintRegistry;

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

    protected function tearDown(): void
    {
        PostalCode::defaults(null);

        parent::tearDown();
    }

    public function testItBindsConstraintRegistry(): void
    {
        $validator = App::make(ConstraintRegistry::class);

        self::assertSame($validator, App::make(ConstraintRegistry::class));
    }

    public function testItRegistersPostalCodeRule(): void
    {
        App::instance(ConstraintRegistry::class, new FakeConstraintRegistry([
            'NL' => new FakeConstraint('1234 AB'),
        ]));

        $validator = Validator::make(
            ['value' => '1234 AB', 'country' => 'NL'],
            ['value' => 'postal_code:NL'],
        );

        self::assertTrue($validator->passes());
    }

    public function testItRegistersPostalCodeReplacer(): void
    {
        App::instance(ConstraintRegistry::class, new FakeConstraintRegistry([
            'NL' => new FakeConstraint('1234 AB'),
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

    public function testItUsesDefaultConfigurationWhenCalledWithoutParameters(): void
    {
        App::instance(ConstraintRegistry::class, new FakeConstraintRegistry([
            'NL' => new FakeConstraint('1234 AB'),
        ]));

        Lang::addLines([
            'validation.postal_code' => 'The :attribute must be a valid :regions postal code.',
        ], locale: 'en');

        PostalCode::defaults(PostalCode::of('NL'));

        $validator = Validator::make(['value' => '1234'], ['value' => 'postal_code']);

        self::assertFalse($validator->passes());
        self::assertSame(['The value must be a valid NL postal code.'], $validator->messages()->all());
    }
}
