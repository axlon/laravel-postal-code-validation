<?php

namespace Axlon\PostalCodeValidation\Contracts;

interface PostalCodeValidator
{
    /**
     * Determine if the given postal code is valid for the specified area.
     *
     * @param string $postalCode
     * @param string $areaCode
     * @return bool
     */
    public function isValid(string $postalCode, string $areaCode): bool;
}
