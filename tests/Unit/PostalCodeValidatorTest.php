<?php

namespace Axlon\PostalCodeValidation\Tests\Unit;

use Axlon\PostalCodeValidation\PostalCodeValidator;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class PostalCodeValidatorTest extends TestCase
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
     * @return \Illuminate\Support\Collection
     */
    public static function provideExamples(): Collection
    {
        $data = require __DIR__ . '/../../resources/examples.php';

        return collect($data)->map(function (string $example, string $country) {
            return [$country, $example];
        });
    }

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->validator = new PostalCodeValidator(
            require __DIR__ . '/../../resources/patterns.php'
        );
    }

    /**
     * Test if the shipped examples pass validation.
     *
     * @param string $country
     * @param string $example
     * @return void
     * @dataProvider provideExamples
     */
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
     * Test pattern override registration.
     *
     * @return void
     */
    public function testPatternOverride(): void
    {
        $this->validator->override('BE', '/override/');
        $this->assertEquals('/override/', $this->validator->patternFor('BE'));
        $this->assertTrue($this->validator->fails('BE', '4000'));
        $this->assertTrue($this->validator->passes('BE', 'override'));
    }

    /**
     * Test pattern override registration using an associative array.
     *
     * @return void
     */
    public function testPatternOverrideViaArray(): void
    {
        $this->validator->override(['FR' => '/override/']);
        $this->assertEquals('/override/', $this->validator->patternFor('FR'));
        $this->assertTrue($this->validator->fails('FR', '33380'));
        $this->assertTrue($this->validator->passes('FR', 'override'));
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
