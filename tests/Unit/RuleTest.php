<?php

namespace Axlon\PostalCodeValidation\Tests\Unit;

use Axlon\PostalCodeValidation\PostalCode;
use PHPUnit\Framework\TestCase;

class RuleTest extends TestCase
{
    /**
     * Test the creation of dependent postal code rules.
     *
     * @return void
     */
    public function testDependentRuleCreation()
    {
        $this->assertEquals('postal_code_for:', (string)PostalCode::forInput());
        $this->assertEquals('postal_code_for:foo,bar,baz', (string)PostalCode::forInput('foo', 'bar')->or('baz'));
    }

    /**
     * Test the creation of explicit postal code rules.
     *
     * @return void
     */
    public function testExplicitRuleCreation()
    {
        $this->assertEquals('postal_code:', (string)PostalCode::forCountry());
        $this->assertEquals('postal_code:foo,bar,baz', (string)PostalCode::forCountry('foo', 'bar')->or('baz'));
    }
}
