<?php

declare(strict_types=1);

namespace Tests\Unit;

use Axlon\PostalCodeValidation\PostalCodeValidator;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class PostalCodeValidatorTest extends TestCase
{
    /**
     * The postal code validator.
     *
     * @var \Axlon\PostalCodeValidation\PostalCodeValidator
     */
    protected $validator;

    /**
     * Get the examples.
     *
     * @return \Illuminate\Support\Collection<string, array{string, string}>
     */
    public static function provideExamples(): Collection
    {
        /** @var array<string, string> $data */
        $data = require __DIR__ . '/../data/examples-by-country.php';

        return collect($data)->map(static function (string $example, string $country) {
            return [$country, $example];
        });
    }

    protected function setUp(): void
    {
        $this->validator = new PostalCodeValidator(
            require __DIR__ . '/../../resources/patterns.php',
        );
    }

    /**
     * @link https://github.com/axlon/laravel-postal-code-validation/issues/35
     */
    public function testCanaryIslands(): void
    {
        $this->assertTrue($this->validator->passes('IC', '38580'));
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
        $this->assertTrue($this->validator->passes($country, $example));
    }

    /**
     * Test whether Great Britain validation fails on an inward code that is too long.
     *
     * @link https://github.com/axlon/laravel-postal-code-validation/issues/13
     * @return void
     */
    public function testGreatBritainInwardCodeMaxLength(): void
    {
        $this->assertFalse($this->validator->passes('GB', 'NN1 5LLL'));
    }

    /**
     * Test whether lower case country codes can be used.
     *
     * @return void
     */
    public function testLowerCaseCountryCode(): void
    {
        $this->assertTrue($this->validator->supports('nl'));
        $this->assertNotNull($this->validator->patternFor('nl'));
        $this->assertTrue($this->validator->passes('nl', '1234 AB'));
    }

    /**
     * Test whether null patterns match any value.
     *
     * @return void
     */
    public function testNullPattern(): void
    {
        $this->assertTrue($this->validator->supports('GH'));
        $this->assertNull($this->validator->patternFor('GH'));
        $this->assertTrue($this->validator->passes('GH', 'any value'));
    }

    /**
     * Test whether unsupported country codes always fail validation.
     *
     * @return void
     */
    public function testUnsupportedCountryCode(): void
    {
        $this->assertFalse($this->validator->supports('XX'));
        $this->assertNull($this->validator->patternFor('XX'));
        $this->assertTrue($this->validator->fails('any value'));
    }
}
