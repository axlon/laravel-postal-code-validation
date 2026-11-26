<?php

namespace Tests\Rules;

use Axlon\PostalCodeValidation\Contracts\PostalCodeValidator;
use Axlon\PostalCodeValidation\Rules\PostalCode;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\MockObject\MockObject;
use stdClass;

#[CoversClass(PostalCode::class)]
final class PostalCodeTest extends TestCase
{
    private PostalCodeValidator&MockObject $engine;

    protected function setUp(): void
    {
        parent::setUp();

        $this->engine = $this->createMock(PostalCodeValidator::class);
        $this->app?->instance(PostalCodeValidator::class, $this->engine);
    }

    public function testItFailsWhenAreaCodeFieldIsEmpty(): void
    {
        $validator = Validator::make(
            ['value' => '1234 AB', 'other' => ''],
            ['value' => PostalCode::of('other')],
        );

        $this->engine->expects(self::never())->method('isValid');

        self::assertFalse($validator->passes());
        self::assertSame(['validation.postal_code'], $validator->errors()->all());
    }

    public function testItFailsWhenAreaFieldIsMissing(): void
    {
        $validator = Validator::make(
            ['value' => '1234 AB'],
            ['value' => PostalCode::of('other')],
        );

        $this->engine->expects(self::never())->method('isValid');

        self::assertFalse($validator->passes());
        self::assertSame(['validation.postal_code'], $validator->errors()->all());
    }

    #[TestWith([null], 'null')]
    #[TestWith([1234], 'int')]
    #[TestWith([12.34], 'float')]
    #[TestWith([true], 'bool')]
    #[TestWith([['NL']], 'array')]
    #[TestWith([new stdClass()], 'object')]
    public function testItFailsWhenAreaCodeFieldIsNotString(mixed $value): void
    {
        $validator = Validator::make(
            ['value' => '1234 AB', 'other' => $value],
            ['value' => PostalCode::of('other')],
        );

        $this->engine->expects(self::never())->method('isValid');

        self::assertFalse($validator->passes());
        self::assertSame(['validation.postal_code'], $validator->errors()->all());
    }

    #[TestWith([null], 'null')]
    #[TestWith([1234], 'int')]
    #[TestWith([12.34], 'float')]
    #[TestWith([true], 'bool')]
    #[TestWith([['1234 AB']], 'array')]
    #[TestWith([new stdClass()], 'object')]
    public function testItFailsWhenValueIsNotString(mixed $value): void
    {
        $validator = Validator::make(
            ['value' => $value],
            ['value' => PostalCode::of('NL')],
        );

        $this->engine->expects(self::never())->method('isValid');

        self::assertFalse($validator->passes());
        self::assertSame(['validation.postal_code'], $validator->errors()->all());
    }

    public function testItFailsWhenValueDoesNotMatchArea(): void
    {
        $validator = Validator::make(
            ['value' => '1234'],
            ['value' => PostalCode::of('NL')],
        );

        $this->engine->expects(self::once())->method('isValid')->with('1234', 'NL')->willReturn(false);

        self::assertFalse($validator->passes());
        self::assertSame(['validation.postal_code'], $validator->errors()->all());
    }

    public function testItFailsWhenValueDoesNotMatchAreaField(): void
    {
        $validator = Validator::make(
            ['value' => '1234', 'other' => 'NL'],
            ['value' => PostalCode::of('other')],
        );

        $this->engine->expects(self::once())->method('isValid')->with('1234', 'NL')->willReturn(false);

        self::assertFalse($validator->passes());
        self::assertSame(['validation.postal_code'], $validator->errors()->all());
    }

    public function testItPassesWhenValueMatchesArea(): void
    {
        $validator = Validator::make(
            ['value' => '1234 AB'],
            ['value' => PostalCode::of('NL')],
        );

        $this->engine->expects(self::once())->method('isValid')->with('1234 AB', 'NL')->willReturn(true);

        self::assertTrue($validator->passes());
    }

    public function testItPassesWhenValueMatchesAreaField(): void
    {
        $validator = Validator::make(
            ['value' => '1234 AB', 'other' => 'NL'],
            ['value' => PostalCode::of(['other'])],
        );

        $this->engine->expects(self::once())->method('isValid')->with('1234 AB', 'NL')->willReturn(true);

        self::assertTrue($validator->passes());
    }

    public function testItPassesWhenValueMatchesOneArea(): void
    {
        $validator = Validator::make(
            ['value' => '1234 AB'],
            ['value' => PostalCode::of(['BE', 'NL', 'LU'])],
        );

        $this->engine->expects(self::exactly(2))->method('isValid')->willReturnMap([
            ['1234 AB', 'BE', false],
            ['1234 AB', 'NL', true],
        ]);

        self::assertTrue($validator->passes());
    }

    public function testItPassesWhenValueMatchesOneAreaField(): void
    {
        $validator = Validator::make(
            ['value' => '1234 AB', 'one' => 'BE', 'three' => 'NL', 'four' => 'LU'],
            ['value' => PostalCode::of(['one', 'two', 'three', 'four'])],
        );

        $this->engine->expects(self::exactly(2))->method('isValid')->willReturnMap([
            ['1234 AB', 'BE', false],
            ['1234 AB', 'NL', true],
        ]);

        self::assertTrue($validator->passes());
    }

    public function testItReplacesAreaCodesInErrorMessage(): void
    {
        Lang::addLines([
            'validation.postal_code' => ':Attribute must be a valid postal code (:areas)',
        ], locale: 'en');

        $validator = Validator::make(
            ['value' => '12345'],
            ['value' => PostalCode::of(['NL', 'BE'])],
        );

        $this->engine->expects(self::exactly(2))->method('isValid')->willReturn(false);

        self::assertFalse($validator->passes());
        self::assertSame(['Value must be a valid postal code (NL, BE)'], $validator->errors()->all());
    }
}
