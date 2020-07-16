<?php

namespace Axlon\PostalCodeValidation\Extensions;

use Axlon\PostalCodeValidation\PatternMatcher;
use Axlon\PostalCodeValidation\PostalCodeExamples;
use Illuminate\Support\Arr;
use Illuminate\Validation\Validator;
use InvalidArgumentException;

class PostalCodeFor
{
    use PostalCodeExamples;

    /**
     * The pattern matcher.
     *
     * @var \Axlon\PostalCodeValidation\PatternMatcher
     */
    protected $matcher;

    /**
     * Create a new PostalCodeFor validator extension.
     *
     * @param \Axlon\PostalCodeValidation\PatternMatcher $matcher
     * @return void
     */
    public function __construct(PatternMatcher $matcher)
    {
        $this->matcher = $matcher;
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

            if (($example = $this->exampleFor($countryCode)) === null) {
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

            if ($this->matcher->passes($parameter, $value)) {
                return true;
            }
        }

        return false;
    }
}
