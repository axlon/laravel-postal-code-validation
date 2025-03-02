<?php

declare(strict_types=1);

namespace Axlon\PostalCodeValidation\Tests\Unit;

use Axlon\PostalCodeValidation\Rules\PostalCode;
use PHPUnit\Framework\TestCase;

final class PostalCodeRuleTest extends TestCase
{
    /**
     * Test the creation of dependent postal code rules.
     *
     * @return void
     */
    public function testDependentRuleCreation(): void
    {
        $this->assertEquals('postal_code_with:', (string) PostalCode::forInput());
        $this->assertEquals('postal_code_with:foo', (string) PostalCode::forInput('foo'));
        $this->assertEquals('postal_code_with:foo,bar,baz', (string) PostalCode::forInput('foo', 'bar')->or('baz'));

        $this->assertEquals('postal_code_with:foo', (string) PostalCode::with('foo'));
        $this->assertEquals('postal_code_with:foo,bar,baz', (string) PostalCode::with('foo')->or('bar')->or('baz'));
    }

    /**
     * Test the creation of explicit postal code rules.
     *
     * @return void
     */
    public function testExplicitRuleCreation(): void
    {
        $this->assertEquals('postal_code:', (string) PostalCode::forCountry());
        $this->assertEquals('postal_code:foo', (string) PostalCode::forCountry('foo'));
        $this->assertEquals('postal_code:foo,bar,baz', (string) PostalCode::forCountry('foo', 'bar')->or('baz'));

        $this->assertEquals('postal_code:foo', (string) PostalCode::for('foo'));
        $this->assertEquals('postal_code:foo,bar,baz', (string) PostalCode::for('foo')->or('bar')->or('baz'));
    }
}
