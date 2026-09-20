<?php

declare(strict_types=1);

namespace Tests\Fakes;

use Axlon\PostalCodeValidation\Constraints\Constraint;

final readonly class FakeConstraint implements Constraint
{
    public function __construct(
        private string $expected,
    ) {
    }

    public function test(string $value): bool
    {
        return $value === $this->expected;
    }
}
