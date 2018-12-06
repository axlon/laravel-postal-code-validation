<?php

namespace Axlon\PostalCodeValidation;

use InvalidArgumentException;
use Sirprize\PostalCodeValidator\Validator as PostalCodeLibrary;

class Validator
{
    protected $library;

    public function __construct()
    {
        $this->library = new PostalCodeLibrary();
    }

    public function validate(string $attribute, string $value, array $countryCodes)
    {
        foreach ($countryCodes as $countryCode) {
            if (!$this->library->hasCountry($countryCode)) {
                throw new InvalidArgumentException(
                    "Country code $countryCode is not currently supported, sorry!\r\n" .
                    'Please go to https://github.com/sirprize/postal-code-validator to add your country code'
                );
            }

            if ($this->library->isValid($countryCode, $value, true)) {
                return true;
            }
        }

        return false;
    }
}
