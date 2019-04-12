<?php

namespace Axlon\PostalCodeValidation;

use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Support\Arr;
use Illuminate\Validation\Validator as BaseValidator;
use Sirprize\PostalCodeValidator\Validator as ValidationEngine;

class Validator
{
    /**
     * The validation engine.
     *
     * @var \Sirprize\PostalCodeValidator\Validator
     */
    protected $engine;

    /**
     * The current request data.
     *
     * @var array
     */
    protected $request;

    /**
     * Create a new postal code validator instance.
     *
     * @param \Sirprize\PostalCodeValidator\Validator $engine
     * @return void
     */
    public function __construct(ValidationEngine $engine)
    {
        $this->engine = $engine;
        $this->request = [];
    }

    /**
     * Fetch a country code from given input.
     *
     * @param string $possibleCountryCode
     * @return string
     */
    protected function fetchCountryCode(string $possibleCountryCode)
    {
        $countryCode = strtoupper($possibleCountryCode);

        if ($this->engine->hasCountry($countryCode)) {
            return $countryCode;
        }

        if (($countryCode = Arr::get($this->request, $possibleCountryCode)) &&
            $this->engine->hasCountry(strtoupper($countryCode))) {
            return strtoupper($countryCode);
        }

        return null;
    }

    /**
     * Replace error message placeholders.
     *
     * @param string $message
     * @param $attribute
     * @param $rule
     * @param $parameters
     * @return mixed
     * @throws \Sirprize\PostalCodeValidator\ValidationException
     */
    public function replacer(string $message, string $attribute, string $rule, array $parameters)
    {
        $countries = [];
        $formats = [];

        foreach ($parameters as $parameter) {
            $countryCode = $this->fetchCountryCode($parameter);
            if (!$this->engine->hasCountry($countryCode)) {
                continue;
            }
            $countries[] = $countryCode;
            $formats = array_merge($formats, $this->engine->getFormats($countryCode));
        }

        $countries = implode(', ', array_unique($countries));
        $formats = implode(', ', array_unique($formats));

        return str_replace([':countries', ':formats'], [$countries, $formats], $message);
    }

    /**
     * Set the current request data.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     */
    protected function setRequest(ValidatorContract $validator)
    {
        if (!$validator instanceof BaseValidator) {
            return;
        }

        $this->request = $validator->getData();
    }

    /**
     * Validate if the given attribute is a valid postal code.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return bool
     * @throws \Sirprize\PostalCodeValidator\ValidationException
     */
    public function validate(string $attribute, $value, array $parameters, ValidatorContract $validator)
    {
        if (!is_string($value) || !$value) {
            return false;
        }

        $this->setRequest($validator);

        foreach ($parameters as $parameter) {
            if (!$countryCode = $this->fetchCountryCode($parameter)) {
                continue;
            }

            if ($this->engine->isValid($countryCode, $value, true)) {
                return true;
            }
        }

        return false;
    }
}
