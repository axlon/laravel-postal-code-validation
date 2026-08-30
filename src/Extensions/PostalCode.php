<?php

declare(strict_types=1);

namespace Axlon\PostalCodeValidation\Extensions;

use Axlon\PostalCodeValidation\Contracts\ConstraintRepository;
use Axlon\PostalCodeValidation\Contracts\ExampleRepository;
use InvalidArgumentException;

final class PostalCode
{
    /**
     * Create a new PostalCode validator extension.
     *
     * @param \Axlon\PostalCodeValidation\Contracts\ConstraintRepository $constraints
     * @param \Axlon\PostalCodeValidation\Contracts\ExampleRepository $examples
     */
    public function __construct(
        protected ConstraintRepository $constraints,
        protected ExampleRepository $examples,
    ) {
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
     * @param mixed $value
     * @param string[] $parameters
     * @return bool
     */
    public function validate(string $attribute, $value, array $parameters): bool
    {
        if ($parameters === []) {
            throw new InvalidArgumentException('Validation rule postal_code requires at least 1 parameter.');
        }

        if (!is_string($value)) {
            return false;
        }

        foreach ($parameters as $parameter) {
            if ($this->constraints->get($parameter)?->matches($value) === true) {
                return true;
            }
        }

        return false;
    }
}
