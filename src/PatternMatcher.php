<?php

namespace Axlon\PostalCodeValidation;

class PatternMatcher
{
    /**
     * The matching patterns.
     *
     * @var array
     */
    protected $patterns;

    /**
     * The matching pattern overrides.
     *
     * @var array
     */
    protected $patternOverrides;

    /**
     * Create a new postal code matcher.
     *
     * @param array $patterns
     * @param array $patternOverrides
     * @return void
     */
    public function __construct(array $patterns, array $patternOverrides)
    {
        $this->patterns = $patterns;
        $this->patternOverrides = $patternOverrides;
    }

    /**
     * Determine if the given postal code(s) are valid for the given country.
     *
     * @param string $countryCode
     * @param string ...$postalCodes
     * @return bool
     */
    public function passes(string $countryCode, string ...$postalCodes): bool
    {
        if (($pattern = $this->patternFor($countryCode)) === null) {
            return true;
        }

        foreach ($postalCodes as $postalCode) {
            if (preg_match($pattern, $postalCode) !== 1) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the matching pattern for the given country.
     *
     * @param string $countryCode
     * @return string|null
     */
    public function patternFor(string $countryCode): ?string
    {
        $countryCode = strtoupper($countryCode);

        return $this->patternOverrides[$countryCode]
            ?? $this->patterns[$countryCode]
            ?? null;
    }

    /**
     * Determine if a matching pattern exists for the given country.
     *
     * @param string $countryCode
     * @return bool
     */
    public function supports(string $countryCode): bool
    {
        $countryCode = strtoupper($countryCode);

        return array_key_exists($countryCode, $this->patternOverrides)
            || array_key_exists($countryCode, $this->patterns);
    }
}
