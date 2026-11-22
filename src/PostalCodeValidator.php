<?php

namespace Axlon\PostalCodeValidation;

/**
 * @internal
 */
final class PostalCodeValidator
{
    private const ALIASES = [
        'IC' => 'ES',
    ];

    /**
     * Create a new postal code validator.
     *
     * @param array<string, string|null> $patterns
     * @return void
     */
    public function __construct(
        private readonly array $patterns,
    ) {
    }

    /**
     * Determine if the given postal code is valid for the given country.
     *
     * @param string $countryCode
     * @param string $postalCode
     * @return bool
     */
    public function passes(string $countryCode, string $postalCode): bool
    {
        $countryCode = strtoupper($countryCode);
        $countryCode = self::ALIASES[$countryCode] ?? $countryCode;

        if (!array_key_exists($countryCode, $this->patterns)) {
            return false;
        }

        $pattern = $this->patterns[$countryCode];

        return $pattern === null || (trim($postalCode) !== '' && preg_match($pattern, $postalCode) === 1);
    }
}
