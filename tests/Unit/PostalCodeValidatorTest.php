<?php

namespace Axlon\PostalCodeValidation\Tests\Unit;

use Axlon\PostalCodeValidation\PostalCodeValidator;
use PHPUnit\Framework\TestCase;

class PostalCodeValidatorTest extends TestCase
{
    /**
     * Test if a country without a pattern (null) will always match.
     *
     * @return void
     */
    public function testCountryWithoutPatternAlwaysMatches(): void
    {
        $matcher = new PostalCodeValidator(['COUNTRY' => null]);

        $this->assertTrue($matcher->supports('country'));
        $this->assertNull($matcher->patternFor('country'));
        $this->assertTrue($matcher->passes('country', 'any-value'));
        $this->assertFalse($matcher->fails('country', 'any-value'));
    }

    /**
     * Test if only valid input passes validation.
     *
     * @return void
     */
    public function testOnlyValidInputMatches(): void
    {
        $matcher = new PostalCodeValidator(['COUNTRY' => '/^valid/']);

        $this->assertTrue($matcher->supports('country'));
        $this->assertEquals('/^valid/', $matcher->patternFor('country'));

        $this->assertTrue($matcher->passes('country', 'valid input'));
        $this->assertTrue($matcher->fails('country', 'invalid input'));
    }

    /**
     * Test if overrides take precedence over regular rules.
     *
     * @return void
     */
    public function testOverridesTakePrecedence(): void
    {
        $matcher = new PostalCodeValidator(['COUNTRY' => '/^old$/']);
        $matcher->override('country', '/^new$/');

        $this->assertFalse($matcher->passes('country', 'old'));
        $this->assertTrue($matcher->passes('country', 'new'));

        $matcher = new PostalCodeValidator(['COUNTRY' => '/^old$/']);
        $matcher->override(['country' => '/^new$/']);

        $this->assertTrue($matcher->fails('country', 'old'));
        $this->assertFalse($matcher->fails('country', 'new'));
    }

    /**
     * Test if the matcher fails postal code for an unsupported country.
     *
     * @return void
     */
    public function testUnsupportedCountryDoesNotMatch(): void
    {
        $matcher = new PostalCodeValidator([]);

        $this->assertFalse($matcher->supports('country'));
        $this->assertNull($matcher->patternFor('country'));
        $this->assertFalse($matcher->passes('country', 'any-value'));
        $this->assertTrue($matcher->fails('country', 'any-value'));
    }
}
