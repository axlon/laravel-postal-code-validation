<?php

namespace Axlon\PostalCodeValidation;

use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
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
     * Parse given input parameter.
     *
     * @param string $parameter
     * @return array|null
     */
    protected function parseInputParameter(string $parameter)
    {
        $countryCode = $input = rtrim($parameter, '?');
        $optional = Str::endsWith($parameter, '?');

        if (Arr::has($this->request, $input)) {
            $countryCode = Arr::get($this->request, $input);
        }

        if (!$this->engine->hasCountry($countryCode = strtoupper($countryCode))) {
            $countryCode = null;
        }

        return [$countryCode, $optional];
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
            [$countryCode, $optional] = $this->parseInputParameter($parameter);

            if (!$countryCode) {
                return $optional;
            }

            if ($this->engine->isValid($countryCode, $value, true)) {
                return true;
            }
        }

        return false;
    }
}
