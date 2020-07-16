<?php

namespace Axlon\PostalCodeValidation\Extensions;

use Axlon\PostalCodeValidation\PatternMatcher;
use Axlon\PostalCodeValidation\PostalCodeExamples;
use InvalidArgumentException;

class PostalCode
{
    use PostalCodeExamples;

    /**
     * The pattern matcher.
     *
     * @var \Axlon\PostalCodeValidation\PatternMatcher
     */
    protected $matcher;

    /**
     * Create a new PostalCode validator extension.
     *
     * @param \Axlon\PostalCodeValidation\PatternMatcher $matcher
     * @return void
     */
    public function __construct(PatternMatcher $matcher)
    {
        $this->matcher = $matcher;
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
            if (($example = $this->exampleFor($parameter)) === null) {
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
            if ($this->matcher->passes($parameter, $value)) {
                return true;
            }
        }

        return false;
    }
}
