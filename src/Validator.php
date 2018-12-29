<?php

namespace Axlon\PostalCodeValidation;

use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Support\Arr;
use Illuminate\Validation\Validator as BaseValidator;
use InvalidArgumentException;
use Sirprize\PostalCodeValidator\Validator as ValidationEngine;

class Validator
{
    protected $engine;
    protected $request;

    public function __construct(ValidationEngine $engine)
    {
        $this->engine = $engine;
        $this->request = [];
    }

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

        throw new InvalidArgumentException('Unsupported country code ' . ($countryCode ?: $possibleCountryCode));
    }

    protected function setRequest(ValidatorContract $validator)
    {
        if (!$validator instanceof BaseValidator) {
            return;
        }

        $this->request = $validator->getData();
    }

    public function validate(string $attribute, string $value, array $parameters, ValidatorContract $validator)
    {
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
