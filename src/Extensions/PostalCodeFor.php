<?php

declare(strict_types=1);

namespace Axlon\PostalCodeValidation\Extensions;

use Axlon\PostalCodeValidation\PostalCodeValidator;
use Illuminate\Support\Arr;
use Illuminate\Validation\Validator;
use InvalidArgumentException;

final class PostalCodeFor
{
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
     * @param \Illuminate\Validation\Validator $validator
     * @return bool
     */
    public function validate(string $attribute, mixed $value, array $parameters, Validator $validator): bool
    {
        if (empty($parameters)) {
            throw new InvalidArgumentException('Validation rule postal_code_with requires at least 1 parameter.');
        }

        if ($value === null) {
            return true;
        }

        if (!is_string($value)) {
            return false;
        }

        $parameters = Arr::only(Arr::dot($validator->getData()), $parameters);

        if ($parameters === []) {
            return true;
        }

        foreach ($parameters as $parameter) {
            if ($parameter === null) {
                continue;
            }

            if ($this->validator->passes($parameter, $value)) {
                return true;
            }
        }

        return false;
    }
}
