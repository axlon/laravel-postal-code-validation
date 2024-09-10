<?php

namespace Axlon\PostalCodeValidation;

class PostalCodeValidator
{
    /**
     * The country codes that are aliases for other country codes.
     */
    private const ALIASES = [
        'IC' => 'ES',
    ];

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
     * @return void
     */
    public function __construct(array $patterns)
    {
        $this->patternOverrides = [];
        $this->patterns = $patterns;
    }

    /**
     * Determine if the given postal code(s) are invalid for the given country.
     *
     * @param string $countryCode
     * @param string|null ...$postalCodes
     * @return bool
     */
    public function fails(string $countryCode, ?string ...$postalCodes): bool
    {
        return !$this->passes($countryCode, ...$postalCodes);
    }

    /**
     * Override pattern matching for the given country.
     *
     * @param array|string $countryCode
     * @param string|null $pattern
     * @return void
     */
    public function override($countryCode, ?string $pattern = null): void
    {
        if (is_array($countryCode)) {
            $this->patternOverrides = array_merge(
                $this->patternOverrides,
                array_change_key_case($countryCode, CASE_UPPER)
            );
        } else {
            $this->patternOverrides[strtoupper($countryCode)] = $pattern;
        }
    }

    /**
     * Determine if the given postal code(s) are valid for the given country.
     *
     * @param string $countryCode
     * @param string|null ...$postalCodes
     * @return bool
     */
    public function passes(string $countryCode, ?string ...$postalCodes): bool
    {
        if (!$this->supports($countryCode)) {
            return false;
        }

        if (($pattern = $this->patternFor($countryCode)) === null) {
            return true;
        }

        foreach ($postalCodes as $postalCode) {
            if ($postalCode === null || trim($postalCode) === '') {
                return false;
            }

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

        if (array_key_exists($countryCode, self::ALIASES)) {
            $countryCode = self::ALIASES[$countryCode];
        }

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
            || array_key_exists($countryCode, $this->patterns)
            || array_key_exists($countryCode, self::ALIASES);
    }
}
