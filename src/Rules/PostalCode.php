<?php

namespace Axlon\PostalCodeValidation\Rules;

class PostalCode
{
    /**
     * The countries which postal code rules should be checked.
     *
     * @var string[]
     */
    protected $countryCodes;

    /**
     * Create a new "postal code" rule instance.
     *
     * @return void
     */
    protected function __construct()
    {
        $this->countryCodes = [];
    }

    /**
     * Add an additional country to the rule.
     *
     * @param string $countryCode
     * @param bool $optional
     * @return $this
     */
    public function andCountry(string $countryCode, bool $optional = false)
    {
        $this->countryCodes[$countryCode] = $optional;
        return $this;
    }

    /**
     * Create a new "postal code" rule instance for given country.
     *
     * @param string $countryCode
     * @param bool $optional
     * @return static
     */
    public static function forCountry(string $countryCode, bool $optional = false)
    {
        return (new static)->andCountry($countryCode, $optional);
    }

    /**
     * Convert the rule to a validation string.
     *
     * @return string
     */
    public function __toString()
    {
        $parameters = [];

        foreach ($this->countryCodes as $countryCode => $optional) {
            $parameters[] = $countryCode . ($optional ? '?' : '');
        }

        return 'postal_code:' . implode(',', $parameters);
    }
}
