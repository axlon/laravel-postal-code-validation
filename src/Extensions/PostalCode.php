<?php

namespace Axlon\PostalCodeValidation\Extensions;

use Axlon\PostalCodeValidation\PostalCodeValidator;
use Axlon\PostalCodeValidation\Support\PostalCodeExamples;
use InvalidArgumentException;

/**
 * @deprecated This class will be removed in 4.0
 */
class PostalCode
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
     * Create a new PostalCode validator extension.
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
     * Replace error message placeholders.
     *
     * @param string $message
     * @param string $attribute
     * @param string $rule
     * @param string[] $parameters
     * @return string
     */
    public function replace(string $message, string $attribute, string $rule, array $parameters): string
    {
        $countries = [];
        $examples = [];

        foreach ($parameters as $parameter) {
            if (($example = $this->examples->get($parameter)) === null) {
                continue;
            }

            $countries[] = $parameter;
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
     * @return bool
     */
    public function validate(string $attribute, ?string $value, array $parameters): bool
    {
        if (empty($parameters)) {
            throw new InvalidArgumentException('Validation rule postal_code requires at least 1 parameter.');
        }

        foreach ($parameters as $parameter) {
            if ($this->validator->passes($parameter, $value)) {
                return true;
            }
        }

        return false;
    }
}
