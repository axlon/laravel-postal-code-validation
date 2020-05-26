<?php

namespace Axlon\PostalCodeValidation\Tests\Unit;

use Axlon\PostalCodeValidation\PatternMatcher;
use PHPUnit\Framework\TestCase;

class PatternMatcherTest extends TestCase
{
    /**
     * Test if the pattern matcher can properly determine what it does and doesn't support.
     *
     * @return void
     */
    public function testCanDetermineSupport(): void
    {
        $matcher = new PatternMatcher(
            ['COUNTRY' => 'pattern', 'COUNTRY WITHOUT PATTERN' => null],
            ['OVERRIDDEN COUNTRY' => 'pattern']
        );

        $this->assertTrue($matcher->supports('country'));
        $this->assertTrue($matcher->supports('country without pattern'));
        $this->assertTrue($matcher->supports('overridden country'));
        $this->assertFalse($matcher->supports('unsupported country'));
    }

    /**
     * Test pattern matching.
     *
     * @return void
     */
    public function testPatternMatching(): void
    {
        $matcher = new PatternMatcher(
            ['COUNTRY' => '/^postal code$/', 'COUNTRY WITHOUT PATTERN' => null, 'OVERRIDDEN COUNTRY' => '/^original$/'],
            ['OVERRIDDEN COUNTRY' => '/^overridden$/']
        );

        $this->assertTrue($matcher->passes('country', 'postal code'));
        $this->assertFalse($matcher->passes('country', 'invalid postal code'));
        $this->assertTrue($matcher->passes('country without pattern', 'postal code'));

        $this->assertFalse($matcher->passes('overridden country', 'original'));
        $this->assertTrue($matcher->passes('overridden country', 'overridden'));
    }
}
