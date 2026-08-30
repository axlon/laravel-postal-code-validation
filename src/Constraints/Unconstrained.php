<?php

declare(strict_types=1);

namespace Axlon\PostalCodeValidation\Constraints;

use Axlon\PostalCodeValidation\Contracts\Constraint;

/**
 * @internal
 */
final class Unconstrained implements Constraint
{
    /**
     * Determine if the given value satisfies the constraint.
     *
     * @param string $value
     * @return bool
     */
    public function matches(string $value): bool
    {
        return true;
    }
}
