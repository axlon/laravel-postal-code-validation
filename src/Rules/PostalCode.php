<?php

namespace Axlon\PostalCodeValidation\Rules;

class PostalCode
{
    protected $countryCodes;

    protected function __construct()
    {
        $this->countryCodes = [];
    }

    public function andCountry(string $countryCode)
    {
        $this->countryCodes[] = $countryCode;
        return $this;
    }

    public static function forCountry(string $countryCode)
    {
        return (new static)->andCountry($countryCode);
    }

    public function __toString()
    {
        return 'postal_code:' . implode($this->countryCodes, ',');
    }
}
