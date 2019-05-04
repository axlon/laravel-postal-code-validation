<?php

namespace Axlon\PostalCodeValidation\Tests\Unit;

use Axlon\PostalCodeValidation\Rules\PostalCode;
use Orchestra\Testbench\TestCase;

class RuleTest extends TestCase
{
    /**
     * Test the fluent API.
     *
     * @return void
     */
    public function testFluentApi()
    {
        $this->assertInstanceOf(PostalCode::class, $instance = PostalCode::for('foo'));
        $this->assertInstanceOf(PostalCode::class, PostalCode::with('bar'));
        $this->assertSame($instance, $instance->and('baz'));
    }

    /**
     * Test the fluent rule to validation string conversion.
     *
     * @return void
     */
    public function testStringConversion()
    {
        $this->assertEquals('postal_code:qux,quux,corge', (string)PostalCode::for('qux', 'quux')->and('corge'));
        $this->assertEquals('postal_code_with:grault,garply,waldo', (string)PostalCode::with('grault', 'garply')->and('waldo'));
    }
}
