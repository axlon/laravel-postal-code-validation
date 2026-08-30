<?php

declare(strict_types=1);

namespace Axlon\PostalCodeValidation\Contracts;

interface Constraint
{
    /**
     * Determine if the given value satisfies the constraint.
     *
     * @param string $value
     * @return bool
     */
    public function matches(string $value): bool;
}
