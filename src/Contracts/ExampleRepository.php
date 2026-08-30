<?php

declare(strict_types=1);

namespace Axlon\PostalCodeValidation\Contracts;

interface ExampleRepository
{
    /**
     * Get a postal code for the specified area.
     *
     * @param string $areaCode
     * @return string|null
     */
    public function get(string $areaCode): ?string;
}
