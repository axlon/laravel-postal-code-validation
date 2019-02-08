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
     * @return $this
     */
    public function andCountry(string $countryCode)
    {
        $this->countryCodes[] = $countryCode;
        return $this;
    }

    /**
     * Create a new "postal code" rule instance for given country.
     *
     * @param string $countryCode
     * @return static
     */
    public static function forCountry(string $countryCode)
    {
        return (new static)->andCountry($countryCode);
    }

    /**
     * Convert the rule to a validation string.
     *
     * @return string
     */
    public function __toString()
    {
        return 'postal_code:' . implode(',', $this->countryCodes);
    }
}
