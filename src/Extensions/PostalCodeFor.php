<?php

namespace Axlon\PostalCodeValidation\Extensions;

use Axlon\PostalCodeValidation\PostalCodeValidator;
use Axlon\PostalCodeValidation\Support\PostalCodeExamples;
use Illuminate\Support\Arr;
use Illuminate\Validation\Validator;
use InvalidArgumentException;

class PostalCodeFor
{
    /**
     * The postal code examples.
     *
     * @var \Axlon\PostalCodeValidation\Support\PostalCodeExamples
     */
    protected $examples;

    /**
     * The pattern matcher.
     *
     * @var \Axlon\PostalCodeValidation\PostalCodeValidator
     */
    protected $validator;

    /**
     * Create a new PostalCodeFor validator extension.
     *
     * @param \Axlon\PostalCodeValidation\PostalCodeValidator $validator
     * @param \Axlon\PostalCodeValidation\Support\PostalCodeExamples $examples
     * @return void
     */
    public function __construct(PostalCodeValidator $validator, PostalCodeExamples $examples)
    {
        $this->examples = $examples;
        $this->validator = $validator;
    }

    /**
     * Get the value of the given attribute.
     *
     * @param string $attribute
     * @param \Illuminate\Validation\Validator $validator
     * @return string|null
     */
    protected function input(string $attribute, Validator $validator): ?string
    {
        return Arr::get($validator->getData(), $attribute);
    }

    /**
     * Replace error message placeholders.
     *
     * @param string $message
     * @param string $attribute
     * @param string $rule
     * @param string[] $parameters
     * @param \Illuminate\Validation\Validator $validator
     * @return string
     */
    public function replace(string $message, string $attribute, string $rule, array $parameters, Validator $validator): string
    {
        $countries = [];
        $examples = [];

        foreach ($parameters as $parameter) {
            if (($countryCode = $this->input($parameter, $validator)) === null) {
                continue;
            }

            if (($example = $this->examples->get($countryCode)) === null) {
                continue;
            }

            $countries[] = $countryCode;
            $examples[] = $example;
        }

        $replacements = [
            $attribute,
            implode(', ', array_unique($countries)),
            implode(', ', array_unique($examples)),
        ];

        return str_replace([':attribute', ':countries', ':examples'], $replacements, $message);
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
    public function validate(string $attribute, ?string $value, array $parameters, Validator $validator): bool
    {
        if (empty($parameters)) {
            throw new InvalidArgumentException('Validation rule postal_code_for requires at least 1 parameter.');
        }

        foreach ($parameters as $parameter) {
            if (($parameter = $this->input($parameter, $validator)) === null) {
                continue;
            }

            if ($this->validator->passes($parameter, $value)) {
                return true;
            }
        }

        return false;
    }
}
