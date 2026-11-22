<?php

namespace Axlon\PostalCodeValidation\Tests\Unit;

use Axlon\PostalCodeValidation\PostalCodeValidator;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PostalCodeValidatorTest extends TestCase
{
    /**
     * The postal code validator.
     *
     * @var \Axlon\PostalCodeValidation\PostalCodeValidator
     */
    protected $validator;

    public static function provideExamples(): Collection
    {
        /** @var non-empty-array<string, string> $data */
        $data = require __DIR__ . '/../../resources/examples.php';

        return collect($data)->map(static function (string $example, string $country) {
            return [$country, $example];
        });
    }

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        /** @var non-empty-array<string, string|null> $data */
        $data = require __DIR__ . '/../../resources/patterns.php';

        $this->validator = new PostalCodeValidator($data);
    }

    /**
     * @link https://github.com/axlon/laravel-postal-code-validation/issues/35
     */
    public function testCanaryIslands(): void
    {
        self::assertTrue($this->validator->passes('IC', '38580'));
    }

    /**
     * Test if the shipped examples pass validation.
     *
     * @param string $country
     * @param string $example
     * @return void
     */
    #[DataProvider('provideExamples')]
    public function testExamplesAreValidPatterns(string $country, string $example): void
    {
        self::assertTrue($this->validator->passes($country, $example));
    }

    /**
     * Test whether Great Britain validation fails on an inward code that is too long.
     *
     * @link https://github.com/axlon/laravel-postal-code-validation/issues/13
     * @return void
     */
    public function testGreatBritainInwardCodeMaxLength(): void
    {
        self::assertFalse($this->validator->passes('GB', 'NN1 5LLL'));
    }

    /**
     * Test whether lower case country codes can be used.
     *
     * @return void
     */
    public function testLowerCaseCountryCode(): void
    {
        self::assertTrue($this->validator->passes('nl', '1234 AB'));
    }

    /**
     * Test whether null patterns match any value.
     *
     * @return void
     */
    public function testNullPattern(): void
    {
        self::assertTrue($this->validator->passes('GH', 'any value'));
    }

    /**
     * Test whether unsupported country codes always fail validation.
     *
     * @return void
     */
    public function testUnsupportedCountryCode(): void
    {
        self::assertFalse($this->validator->passes('XX', 'any value'));
    }
}
