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
        private PostalCodeValidator $validator,
    ) {
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
        if ($parameters === []) {
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
