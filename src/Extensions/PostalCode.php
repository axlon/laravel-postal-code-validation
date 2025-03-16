<?php

declare(strict_types=1);

namespace Axlon\PostalCodeValidation\Extensions;

use Axlon\PostalCodeValidation\PostalCodeValidator;
use InvalidArgumentException;

final class PostalCode
{
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
     * @return void
     */
    public function __construct(PostalCodeValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Validate the given attribute.
     *
     * @param string $attribute
     * @param mixed $value
     * @param string[] $parameters
     * @return bool
     */
    public function validate(string $attribute, mixed $value, array $parameters): bool
    {
        if (empty($parameters)) {
            throw new InvalidArgumentException('Validation rule postal_code requires at least 1 parameter.');
        }

        if ($value === null) {
            return true;
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
