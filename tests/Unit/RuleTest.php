<?php

namespace Axlon\PostalCodeValidation\Tests\Unit;

use Axlon\PostalCodeValidation\Rules\PostalCode;
use Orchestra\Testbench\TestCase;

class RuleTest extends TestCase
{
    /**
     * Test the creation of dependant postal code rules.
     *
     * @return void
     */
    public function testDependantRuleCreation()
    {
        $this->assertEquals('postal_code_for:', (string)PostalCode::forInput());
        $this->assertEquals('postal_code_for:foo,bar,baz', (string)PostalCode::forInput('foo', 'bar')->and('baz'));
    }

    /**
     * Test the creation of explicit postal code rules.
     *
     * @return void
     */
    public function testExplicitRuleCreation()
    {
        $this->assertEquals('postal_code:', (string)PostalCode::forCountry());
        $this->assertEquals('postal_code:foo,bar,baz', (string)PostalCode::forCountry('foo', 'bar')->and('baz'));
    }
}
