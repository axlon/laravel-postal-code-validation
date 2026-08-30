<?php

declare(strict_types=1);

namespace Tests\Constraints;

use Axlon\PostalCodeValidation\Constraints\Unconstrained;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Unconstrained::class)]
final class UnconstrainedTest extends TestCase
{
    public function testItMatches(): void
    {
        $constraint = new Unconstrained();

        self::assertTrue($constraint->matches('1234 AB'));
    }
}
