<?php

namespace Tests\Unit;

use Axlon\PostalCodeValidation\PostalCodeValidator;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(PostalCodeValidator::class)]
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
        $data = require __DIR__ . '/../Fixtures/examples.php';

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
        self::assertTrue($this->validator->isValid('38580', 'IC'));
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
        self::assertTrue($this->validator->isValid($example, $country));
    }

    /**
     * Test whether Great Britain validation fails on an inward code that is too long.
     *
     * @link https://github.com/axlon/laravel-postal-code-validation/issues/13
     * @return void
     */
    public function testGreatBritainInwardCodeMaxLength(): void
    {
        self::assertFalse($this->validator->isValid('NN1 5LLL', 'GB'));
    }

    /**
     * Test whether lower case country codes can be used.
     *
     * @return void
     */
    public function testLowerCaseCountryCode(): void
    {
        self::assertTrue($this->validator->isValid('1234 AB', 'nl'));
    }

    /**
     * Test whether null patterns match any value.
     *
     * @return void
     */
    public function testNullPattern(): void
    {
        self::assertTrue($this->validator->isValid('any value', 'GH'));
    }

    /**
     * Test whether unsupported country codes always fail validation.
     *
     * @return void
     */
    public function testUnsupportedCountryCode(): void
    {
        self::assertFalse($this->validator->isValid('any value', 'XX'));
    }
}
