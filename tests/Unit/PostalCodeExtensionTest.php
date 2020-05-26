<?php

namespace Axlon\PostalCodeValidation\Tests\Unit;

use Axlon\PostalCodeValidation\Extensions\PostalCode;
use Axlon\PostalCodeValidation\PatternMatcher;
use InvalidArgumentException;
use Mockery;
use PHPUnit\Framework\TestCase;

class PostalCodeExtensionTest extends TestCase
{
    /**
     * The pattern matcher.
     *
     * @var \Axlon\PostalCodeValidation\PatternMatcher|\Mockery\MockInterface
     */
    protected $matcher;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->matcher = Mockery::mock(PatternMatcher::class);
    }

    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
        Mockery::close();
    }

    /**
     * Test if validation of empty input always fails.
     *
     * @return void
     */
    public function testValidationOfEmptyInputFails(): void
    {
        $extension = new PostalCode($this->matcher);

        $this->assertFalse($extension->validate('attribute', null, ['country']));
        $this->assertFalse($extension->validate('attribute', '', ['country']));
    }

    /**
     * Test if validation of a non-matching postal code fails.
     *
     * @return void
     */
    public function testValidationOfInvalidPostalCodeFails(): void
    {
        $extension = new PostalCode($this->matcher);

        $this->matcher->expects('supports')->with('country')->once()->andReturnTrue();
        $this->matcher->expects('passes')->with('country', 'postal code')->once()->andReturnFalse();

        $this->assertFalse($extension->validate('attribute', 'postal code', ['country']));
    }

    /**
     * Test if validation using an unsupported country code fails.
     *
     * @return void
     */
    public function testValidationOfUnsupportedCountryFails(): void
    {
        $extension = new PostalCode($this->matcher);

        $this->matcher->expects('supports')->with('country')->once()->andReturnFalse();
        $this->assertFalse($extension->validate('attribute', 'postal code', ['country']));
    }

    /**
     * Test if validation of a matching postal code passes.
     *
     * @return void
     */
    public function testValidationOfValidPostalCodePasses(): void
    {
        $extension = new PostalCode($this->matcher);

        $this->matcher->expects('supports')->with('country')->once()->andReturnTrue();
        $this->matcher->expects('passes')->with('country', 'postal code')->once()->andReturnTrue();

        $this->assertTrue($extension->validate('attribute', 'postal code', ['country']));
    }

    /**
     * Test if validation will try all countries even if earlier ones fail.
     *
     * @return void
     */
    public function testValidationTriesAllCountries(): void
    {
        $extension = new PostalCode($this->matcher);

        $this->matcher->expects('supports')->with('failing country')->once()->andReturnTrue();
        $this->matcher->expects('passes')->with('failing country', 'postal code')->once()->andReturnFalse();

        $this->matcher->expects('supports')->with('passing country')->once()->andReturnTrue();
        $this->matcher->expects('passes')->with('passing country', 'postal code')->once()->andReturnTrue();

        $this->assertTrue($extension->validate('attribute', 'postal code', ['failing country', 'passing country']));
    }

    /**
     * Test if validation without parameters throws an exception.
     *
     * @return void
     */
    public function testValidationWithoutParametersThrows(): void
    {
        $extension = new PostalCode($this->matcher);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Validation rule postal_code requires at least 1 parameter.');

        $extension->validate('attribute', 'postal_code', []);
    }
}
