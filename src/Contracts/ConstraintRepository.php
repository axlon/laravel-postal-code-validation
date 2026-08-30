<?php

declare(strict_types=1);

namespace Axlon\PostalCodeValidation\Contracts;

interface ConstraintRepository
{
    /**
     * Get the postal code constraint for the specified area.
     *
     * @param string $areaCode
     * @return \Axlon\PostalCodeValidation\Contracts\Constraint|null
     */
    public function get(string $areaCode): ?Constraint;
}
