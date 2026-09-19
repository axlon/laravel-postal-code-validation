<?php

declare(strict_types=1);

namespace Axlon\PostalCodeValidation\Extensions;

use Axlon\PostalCodeValidation\PostalCodeValidator;
use InvalidArgumentException;

final class PostalCode
{
    /**
     * Create a new PostalCode validator extension.
     *
     * @param \Axlon\PostalCodeValidation\PostalCodeValidator $validator
     * @return void
     */
    public function __construct(
        protected PostalCodeValidator $validator,
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

        foreach ($parameters as $parameter) {
            if ($this->validator->supports($parameter)) {
                $countries[] = $parameter;
            }
        }

        $replacements = [
            $attribute,
            implode(', ', array_unique($countries)),
        ];

        return str_replace([':attribute', ':countries'], $replacements, $message);
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
            if ($this->validator->passes($parameter, $value)) {
                return true;
            }
        }

        return false;
    }
}
