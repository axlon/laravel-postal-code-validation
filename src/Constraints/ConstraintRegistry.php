<?php

declare(strict_types=1);

namespace Axlon\PostalCodeValidation\Constraints;

interface ConstraintRegistry
{
    /**
     * Retrieve a constraint for the specified region.
     *
     * @param string $regionCode
     * @return \Axlon\PostalCodeValidation\Constraints\Constraint|null
     */
    public function get(string $regionCode): ?Constraint;
}
