<?php

declare(strict_types=1);

namespace Tests\Rules;

use Axlon\PostalCodeValidation\PostalCodeValidator;
use Axlon\PostalCodeValidation\Rules\PostalCode;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(PostalCode::class)]
#[UsesClass(PostalCodeValidator::class)]
final class PostalCodeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        App::instance(PostalCodeValidator::class, new PostalCodeValidator([
            'BE' => '/^\d{4}$/i',
            'NL' => '/^\d{4} ?[A-Z]{2}$/i',
        ]));
    }

    public function testItFailsWhenRegionFieldIsMissing(): void
    {
        $validator = Validator::make(
            ['value' => '1234 AB'],
            ['value' => PostalCode::of('country')],
        );

        self::assertFalse($validator->passes());
        self::assertSame(['validation.postal_code'], $validator->errors()->all());
    }

    #[TestWith([['NL']], 'array')]
    #[TestWith([null], 'null')]
    #[TestWith([1.23], 'float')]
    #[TestWith([1234], 'int')]
    public function testItFailsWhenRegionFieldIsNotString(mixed $value): void
    {
        $validator = Validator::make(
            ['value' => '1234 AB', 'country' => $value],
            ['value' => PostalCode::of('country')],
        );

        self::assertFalse($validator->passes());
        self::assertSame(['validation.postal_code'], $validator->errors()->all());
    }

    #[TestWith(['XX'], 'unknown')]
    #[TestWith(['nl'], 'lowercase')]
    #[TestWith(['NLD'], 'wrong format')]
    public function testItFailsWhenRegionFieldIsNotSupported(string $region): void
    {
        $validator = Validator::make(
            ['value' => '1234 AB', 'country' => $region],
            ['value' => PostalCode::of('country')],
        );

        self::assertFalse($validator->passes());
        self::assertSame(['validation.postal_code'], $validator->errors()->all());
    }

    #[TestWith(['XX'], 'unknown')]
    #[TestWith(['nl'], 'lowercase')]
    #[TestWith(['NLD'], 'wrong format')]
    public function testItFailsWhenRegionIsNotSupported(string $region): void
    {
        $validator = Validator::make(
            ['value' => '1234 AB'],
            ['value' => PostalCode::of($region)],
        );

        self::assertFalse($validator->passes());
        self::assertSame(['validation.postal_code'], $validator->errors()->all());
    }

    public function testItFailsWhenValueDoesNotMatchRegion(): void
    {
        $validator = Validator::make(
            ['value' => '12345'],
            ['value' => PostalCode::of(['NL', 'BE'])],
        );

        self::assertFalse($validator->passes());
        self::assertSame(['validation.postal_code'], $validator->errors()->all());
    }

    public function testItFailsWhenValueDoesNotMatchRegionField(): void
    {
        $validator = Validator::make(
            ['value' => '1234', 'country' => 'NL'],
            ['value' => PostalCode::of('country')],
        );

        self::assertFalse($validator->passes());
        self::assertSame(['validation.postal_code'], $validator->errors()->all());
    }

    #[TestWith([['1234 AB']], 'array')]
    #[TestWith([null], 'null')]
    #[TestWith([1.23], 'float')]
    #[TestWith([1234], 'int')]
    public function testItFailsWhenValueIsNotString(mixed $value): void
    {
        $validator = Validator::make(
            ['value' => $value],
            ['value' => PostalCode::of('NL')],
        );

        self::assertFalse($validator->passes());
        self::assertSame(['validation.postal_code'], $validator->errors()->all());
    }

    public function testItFailsWithoutArguments(): void
    {
        $validator = Validator::make(
            ['value' => '1234 AB'],
            ['value' => PostalCode::of([])],
        );

        self::assertFalse($validator->passes());
        self::assertSame(['validation.postal_code'], $validator->errors()->all());
    }

    public function testItPassesWhenValueMatchesRegion(): void
    {
        $validator = Validator::make(
            ['value' => '1234 AB'],
            ['value' => PostalCode::of('NL')],
        );

        self::assertTrue($validator->passes());
    }

    public function testItPassesWhenValueMatchesAnyRegion(): void
    {
        $validator = Validator::make(
            ['value' => '1234 AB'],
            ['value' => PostalCode::of('BE', 'NL')],
        );

        self::assertTrue($validator->passes());
    }

    public function testItPassesWhenValueMatchesRegionField(): void
    {
        $validator = Validator::make(
            ['value' => '1234 AB', 'country' => 'NL'],
            ['value' => PostalCode::of('country')],
        );

        self::assertTrue($validator->passes());
        self::assertEmpty($validator->errors()->all());
    }

    public function testItPassesWhenValueMatchesAnyRegionField(): void
    {
        $validator = Validator::make(
            ['value' => '1234 AB', 'country' => 'BE', 'shipping' => ['country' => 'NL']],
            ['value' => PostalCode::of('country', 'shipping.country')],
        );

        self::assertTrue($validator->passes());
        self::assertEmpty($validator->errors()->all());
    }

    public function testItPassesWhenValueMatchesNestedRegionField(): void
    {
        $validator = Validator::make(
            ['value' => '1234 AB', 'shipping' => ['country' => 'NL']],
            ['value' => PostalCode::of('shipping.country')],
        );

        self::assertTrue($validator->passes());
        self::assertEmpty($validator->errors()->all());
    }

    public function testItReplacesRegionsInErrorMessage(): void
    {
        Lang::addLines([
            'validation.postal_code' => 'The :attribute must be a valid :regions postal code.',
        ], 'en');

        $validator = Validator::make(
            ['value' => '12345'],
            ['value' => PostalCode::of('NL', 'BE')],
        );

        self::assertFalse($validator->passes());
        self::assertSame(['The value must be a valid NL, BE postal code.'], $validator->errors()->all());
    }

    public function testItReplacesResolvedRegionsInErrorMessage(): void
    {
        Lang::addLines([
            'validation.postal_code' => 'The :attribute must be a valid :regions postal code.',
        ], 'en');

        $validator = Validator::make(
            ['value' => '1234 AB', 'country' => 'BE'],
            ['value' => PostalCode::of('missing', 'country')],
        );

        self::assertFalse($validator->passes());
        self::assertSame(['The value must be a valid BE postal code.'], $validator->errors()->all());
    }
}
