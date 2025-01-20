<?php

declare(strict_types=1);

namespace Axlon\PostalCodeValidation\Support;

final class PostalCodeExamples
{
    /**
     * The postal code examples.
     *
     * @var array<string, string>|null
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
            /** @var array<string, string> $data */
            $data = require __DIR__ . '/../../resources/examples.php';
            $this->examples = $data;
        }

        return $this->examples[strtoupper($countryCode)] ?? null;
    }
}
