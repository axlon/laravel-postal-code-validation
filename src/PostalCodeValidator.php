<?php

namespace Axlon\PostalCodeValidation;

use InvalidArgumentException;

class PostalCodeValidator
{
    /**
     * The validation rules for each country.
     *
     * @var array
     */
    protected $rules;

    /**
     * Create a new postal code validator.
     *
     * @param array $rules
     * @return void
     */
    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * Get an example postal code for the country.
     *
     * @param string $countryCode
     * @return string|null
     */
    public function getExample(string $countryCode)
    {
        return $this->rules[strtoupper($countryCode)]['example'] ?? null;
    }

    /**
     * Get the validation rule for the country.
     *
     * @param string $countryCode
     * @return string|null
     */
    protected function getRule(string $countryCode)
    {
        return $this->rules[strtoupper($countryCode)]['pattern'];
    }

    /**
     * Determine if the country code is supported.
     *
     * @param string|null $countryCode
     * @return bool
     */
    public function supports(?string $countryCode)
    {
        return $countryCode && array_key_exists(strtoupper($countryCode), $this->rules);
    }

    /**
     * Validate the postal code.
     *
     * @param string $countryCode
     * @param string $postalCode
     * @return bool
     */
    public function validate(string $countryCode, string $postalCode)
    {
        if (!$this->supports($countryCode)) {
            throw new InvalidArgumentException("Unsupported country code $countryCode");
        }

        if (is_null($pattern = $this->getRule($countryCode))) {
            return true;
        }

        return (bool)preg_match($pattern, $postalCode);
    }
}
