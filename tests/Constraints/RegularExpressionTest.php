<?php

declare(strict_types=1);

namespace Tests\Constraints;

use Axlon\PostalCodeValidation\Constraints\RegularExpression;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

#[CoversClass(RegularExpression::class)]
final class RegularExpressionTest extends TestCase
{
    #[TestWith(['foo', true])]
    #[TestWith(['bar', false])]
    public function testItPasses(string $value, bool $expected): void
    {
        $constraint = new RegularExpression('/foo/');
        $actual = $constraint->matches($value);

        self::assertSame($expected, $actual);
    }
}
