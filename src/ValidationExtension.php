<?php

namespace Axlon\PostalCodeValidation;

use Illuminate\Support\Arr;
use Illuminate\Validation\Validator;

class ValidationExtension
{
    /**
     * The postal code validator.
     *
     * @var \Axlon\PostalCodeValidation\PostalCodeValidator
     */
    protected $postalCodeValidator;

    /**
     * Create a new postal code validation extension.
     *
     * @param \Axlon\PostalCodeValidation\PostalCodeValidator $postalCodeValidator
     * @return void
     */
    public function __construct(PostalCodeValidator $postalCodeValidator)
    {
        $this->postalCodeValidator = $postalCodeValidator;
    }

    /**
     * Get the values of the given attributes.
     *
     * @param string[] $attributes
     * @param \Illuminate\Validation\Validator $validator
     * @return string[]
     */
    protected function getValues(array $attributes, Validator $validator): array
    {
        $attributes = Arr::only(Arr::dot($validator->getData()), $attributes);

        return array_filter($attributes, function ($value) {
            return is_string($value) && $value !== '';
        });
    }

    /**
     * Validate the given attribute.
     *
     * @param string $attribute
     * @param string|null $value
     * @param string[] $parameters
     * @param \Illuminate\Validation\Validator $validator
     * @return bool
     */
    public function validatePostalCode(string $attribute, ?string $value, array $parameters, Validator $validator): bool
    {
        $validator->requireParameterCount(1, $parameters, 'postal_code');

        if ($value === null || $value === '') {
            return false;
        }

        foreach ($parameters as $parameter) {
            if (!$this->postalCodeValidator->supports($parameter)) {
                return false;
            }

            if ($this->postalCodeValidator->validate($parameter, $value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Validate the given attribute.
     *
     * @param string $attribute
     * @param string|null $value
     * @param string[] $parameters
     * @param \Illuminate\Validation\Validator $validator
     * @return bool
     */
    public function validatePostalCodeFor(string $attribute, ?string $value, array $parameters, Validator $validator): bool
    {
        $validator->requireParameterCount(1, $parameters, 'postal_code_for');

        if (empty($codes = $this->getValues($parameters, $validator))) {
            return true;
        }

        if ($value === null || $value === '') {
            return false;
        }

        foreach ($codes as $code) {
            if (!$this->postalCodeValidator->supports($code)) {
                continue;
            }

            if ($this->postalCodeValidator->validate($code, $value)) {
                return true;
            }
        }

        return false;
    }
}
