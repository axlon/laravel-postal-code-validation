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
    protected ?array $examples = null;

    /**
     * Get a postal code example for the given country.
     *
     * @param string $countryCode
     * @return string|null
     */
    public function get(string $countryCode): ?string
    {
        if ($this->examples === null) {
            /** @var array<string, string> $examples */
            $examples = require __DIR__ . '/../../resources/examples.php';
            $this->examples = $examples;
        }

        return $this->examples[strtoupper($countryCode)] ?? null;
    }
}
