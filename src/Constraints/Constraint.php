<?php

declare(strict_types=1);

namespace Axlon\PostalCodeValidation\Constraints;

interface Constraint
{
    /**
     * Determine whether the given value passes the constraint.
     *
     * @param string $value
     * @return bool
     */
    public function test(string $value): bool;
}
