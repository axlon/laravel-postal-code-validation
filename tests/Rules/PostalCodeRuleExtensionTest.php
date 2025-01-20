<?php

declare(strict_types=1);

namespace Tests\Rules;

use Generator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class PostalCodeRuleExtensionTest extends TestCase
{
    public static function provideExamples(): Generator
    {
        /** @var array<string, string> $data */
        $data = require __DIR__ . '/../../resources/examples.php';

        yield from Collection::make($data)->map(static function (string $example, string $countryCode) {
            return [$countryCode, $example];
        });
    }

    public function testItFailsWhenCountryCodeFieldIsInvalid(): void
    {
        $data = [
            'country' => 'nL',
            'value' => '1234 AB',
        ];

        $rules = [
            'value' => 'postal_code:country',
        ];

        $validator = Validator::make($data, $rules);

        self::assertFalse($validator->passes());
        self::assertContains('validation.postal_code', $validator->errors()->all());
    }

    public function testItFailsWhenCountryCodeFieldIsMissing(): void
    {
        $data = ['value' => '1234 AB'];
        $rules = ['value' => 'postal_code:country'];

        $validator = Validator::make($data, $rules);

        self::assertFalse($validator->passes());
        self::assertContains('validation.postal_code', $validator->errors()->all());
    }

    public function testItFailsWhenCountryCodeFieldIsNull(): void
    {
        $data = [
            'country' => null,
            'value' => '1234 AB',
        ];

        $rules = [
            'value' => 'postal_code:country',
        ];

        $validator = Validator::make($data, $rules);

        self::assertFalse($validator->passes());
        self::assertContains('validation.postal_code', $validator->errors()->all());
    }

    public function testItFailsWhenCountryCodeIsInvalid(): void
    {
        $data = ['value' => '1234 AB'];
        $rules = ['value' => 'postal_code:nL'];

        $validator = Validator::make($data, $rules);

        self::assertFalse($validator->passes());
        self::assertContains('validation.postal_code', $validator->errors()->all());
    }

    public function testItFailsWhenValueDoesNotMatchAnyCountryCode(): void
    {
        $data = ['value' => '95014'];
        $rules = ['value' => 'postal_code:NL,BE'];

        $validator = Validator::make($data, $rules);

        self::assertFalse($validator->passes());
        self::assertContains('validation.postal_code', $validator->errors()->all());
    }

    public function testItFailsWhenValueDoesNotMatchAnyCountryCodeField(): void
    {
        $data = [
            'country_1' => 'NL',
            'country_2' => 'BE',
            'value' => '95014',
        ];

        $rules = ['value' => 'postal_code:country_1,country_2'];

        $validator = Validator::make($data, $rules);

        self::assertFalse($validator->passes());
        self::assertContains('validation.postal_code', $validator->errors()->all());
    }

    public function testItPassesWhenValueIsMissing(): void
    {
        $data = [];
        $rules = ['value' => 'postal_code:NL'];

        $validator = Validator::make($data, $rules);

        self::assertTrue($validator->passes());
    }

    public function testItPassesWhenValueIsNull(): void
    {
        $data = ['value' => null];
        $rules = ['value' => 'postal_code:NL'];

        $validator = Validator::make($data, $rules);

        self::assertTrue($validator->passes());
    }

    public function testItPassesWhenValueMatchesAnyCountryCode(): void
    {
        $data = ['value' => '4000'];
        $rules = ['value' => 'postal_code:NL,BE'];

        $validator = Validator::make($data, $rules);

        self::assertTrue($validator->passes());
    }

    public function testItPassesWhenValueMatchesAnyCountryCodeField(): void
    {
        $data = [
            'country_1' => 'NL',
            'country_2' => 'BE',
            'value' => '4000',
        ];

        $rules = [
            'value' => 'postal_code:country_1,country_2',
        ];

        $validator = Validator::make($data, $rules);

        self::assertTrue($validator->passes());
    }

    #[DataProvider('provideExamples')]
    public function testItPassesWhenValueMatchesCountryCode(string $countryCode, string $example): void
    {
        $data = ['value' => $example];
        $rules = ['value' => "postal_code:$countryCode"];

        $validator = Validator::make($data, $rules);

        self::assertTrue($validator->passes());
    }

    public function testItPassesWhenValueMatchesCountryCodeField(): void
    {
        $data = [
            'country' => 'NL',
            'value' => '1234 AB',
        ];

        $rules = [
            'value' => 'postal_code:NL',
        ];

        $validator = Validator::make($data, $rules);

        self::assertTrue($validator->passes());
    }

    public function testItThrowsWithoutParameters(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Postal code validation requires at least 1 parameter');

        Validator::validate(['value' => '1234 AB'], ['value' => 'postal_code']);
    }
}
