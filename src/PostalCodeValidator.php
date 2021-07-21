<?php

namespace Axlon\PostalCodeValidation;

use Axlon\PostalCodeValidation\Contracts\Ruleset;
use Illuminate\Support\Arr;
use Illuminate\Validation\Validator;
use InvalidArgumentException;

class PostalCodeValidator
{
    /**
     * The validation rules.
     *
     * @var \Axlon\PostalCodeValidation\Contracts\Ruleset
     */
    protected $rules;

    /**
     * Create a new postal code validator instance.
     *
     * @param \Axlon\PostalCodeValidation\Contracts\Ruleset $rules
     * @return void
     */
    public function __construct(Ruleset $rules)
    {
        $this->rules = $rules;
    }

    /**
     * Determine whether the given value can be used as a string.
     *
     * @param mixed $value
     * @return bool
     */
    protected function isStringable($value): bool
    {
        return (is_object($value) && method_exists($value, '__toString')) || is_scalar($value);
    }

    /**
     * Prepare the other values for validation.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @param array $attributes
     * @return array
     */
    protected function prepareOthers(Validator $validator, array $attributes): array
    {
        $attributes = array_filter($attributes, function (string $attribute) use ($validator) {
            return !$validator->errors()->has($attribute);
        });

        $others = Arr::only(Arr::dot($validator->getData()), $attributes);

        return array_filter($others, function ($other) {
            return $this->isStringable($other);
        });
    }

    /**
     * Replace all place-holders for the postal_code rule.
     *
     * @param string $message
     * @param string $attribute
     * @param string $rule
     * @param string[] $parameters
     * @return string
     */
    public function replacePostalCode(string $message, string $attribute, string $rule, array $parameters): string
    {
        $examples = [];
        $parameters = array_map('strtoupper', $parameters);

        foreach ($parameters as $parameter) {
            if ($this->rules->hasExample($parameter)) {
                $examples[] = $this->rules->getExample($parameter);
            }
        }

        $replacements = [
            ':countries' => implode(', ', array_unique($parameters)),
            ':examples' => implode(', ', array_unique($examples)),
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $message);
    }

    /**
     * Replace all place-holders for the postal_code_with rule.
     *
     * @param string $message
     * @param string $attribute
     * @param string $rule
     * @param string[] $parameters
     * @param \Illuminate\Validation\Validator $validator
     * @return string
     */
    public function replacePostalCodeWith(string $message, string $attribute, string $rule, array $parameters, Validator $validator): string
    {
        $others = $this->prepareOthers($validator, $parameters);

        return $this->replacePostalCode($message, $attribute, $rule, $others);
    }

    /**
     * Validate that an attribute is a valid postal code.
     *
     * @param string $attribute
     * @param mixed $value
     * @param string[] $parameters
     * @return bool
     */
    public function validatePostalCode(string $attribute, $value, array $parameters): bool
    {
        if (empty($parameters)) {
            throw new InvalidArgumentException('Validation rule postal_code requires at least 1 parameter.');
        }

        if (!$this->isStringable($value)) {
            return false;
        }

        $parameters = array_map('strtoupper', $parameters);

        foreach ($parameters as $parameter) {
            if (!$this->rules->hasRule($parameter)) {
                continue;
            }

            if (preg_match($this->rules->getRule($parameter), (string)$value) === 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * Validate that an attribute is a valid postal code.
     *
     * @param string $attribute
     * @param mixed $value
     * @param string[] $parameters
     * @param \Illuminate\Validation\Validator $validator
     * @return bool
     */
    public function validatePostalCodeWith(string $attribute, $value, array $parameters, Validator $validator): bool
    {
        if (empty($parameters)) {
            throw new InvalidArgumentException('Validation rule postal_code_with requires at least 1 parameter.');
        }

        $others = $this->prepareOthers($validator, $parameters);

        if ($others !== []) {
            return $this->validatePostalCode($attribute, $value, $others);
        }

        return false;
    }
}
