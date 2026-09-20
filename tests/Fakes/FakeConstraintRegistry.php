<?php

declare(strict_types=1);

namespace Tests\Fakes;

use Axlon\PostalCodeValidation\Constraints\Constraint;
use Axlon\PostalCodeValidation\Constraints\ConstraintRegistry;

final readonly class FakeConstraintRegistry implements ConstraintRegistry
{
    /**
     * @param array<non-empty-string, \Axlon\PostalCodeValidation\Constraints\Constraint> $constraints
     */
    public function __construct(
        private array $constraints,
    ) {
    }

    public function get(string $regionCode): ?Constraint
    {
        return $this->constraints[$regionCode] ?? null;
    }
}
