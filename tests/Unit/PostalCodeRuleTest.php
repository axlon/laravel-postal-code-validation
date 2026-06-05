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
        self::assertSame('postal_code_with:', (string) PostalCode::forInput());
        self::assertSame('postal_code_with:foo', (string) PostalCode::forInput('foo'));
        self::assertSame('postal_code_with:foo,bar,baz', (string) PostalCode::forInput('foo', 'bar')->or('baz'));

        self::assertSame('postal_code_with:foo', (string) PostalCode::with('foo'));
        self::assertSame('postal_code_with:foo,bar,baz', (string) PostalCode::with('foo')->or('bar')->or('baz'));
    }

    /**
     * Test the creation of explicit postal code rules.
     *
     * @return void
     */
    public function testExplicitRuleCreation(): void
    {
        self::assertSame('postal_code:', (string) PostalCode::forCountry());
        self::assertSame('postal_code:foo', (string) PostalCode::forCountry('foo'));
        self::assertSame('postal_code:foo,bar,baz', (string) PostalCode::forCountry('foo', 'bar')->or('baz'));

        self::assertSame('postal_code:foo', (string) PostalCode::for('foo'));
        self::assertSame('postal_code:foo,bar,baz', (string) PostalCode::for('foo')->or('bar')->or('baz'));
    }
}
