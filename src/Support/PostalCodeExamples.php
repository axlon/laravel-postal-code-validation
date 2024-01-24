<?php

namespace Axlon\PostalCodeValidation\Support;

class PostalCodeExamples
{
    /**
     * The postal code examples.
     *
     * @var array|null
     */
    protected $examples;

    /**
     * Get a postal code example for the given country.
     *
     * @param string $countryCode
     * @return string|null
     */
    public function get(string $countryCode): ?string
    {
        if ($this->examples === null) {
            $this->examples = require __DIR__ . '/../../resources/examples.php';
        }

        return $this->examples[strtoupper($countryCode)] ?? null;
    }
}
