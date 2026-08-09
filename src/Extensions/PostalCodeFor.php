<?php

declare(strict_types=1);

namespace Axlon\PostalCodeValidation\Extensions;

use Axlon\PostalCodeValidation\PostalCodeValidator;
use Axlon\PostalCodeValidation\Support\PostalCodeExamples;
use Illuminate\Support\Arr;
use Illuminate\Validation\Validator;
use InvalidArgumentException;

final class PostalCodeFor
{
    /**
     * Create a new PostalCodeFor validator extension.
     *
     * @param \Axlon\PostalCodeValidation\PostalCodeValidator $validator
     * @param \Axlon\PostalCodeValidation\Support\PostalCodeExamples $examples
     */
    public function __construct(
        protected PostalCodeValidator $validator,
        protected PostalCodeExamples $examples,
    ) {
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
    public function replace(
        string $message,
        string $attribute,
        string $rule,
        array $parameters,
        Validator $validator,
    ): string {
        $countries = [];
        $examples = [];

        foreach ($parameters as $parameter) {
            if (!is_string($input = Arr::get($validator->getData(), $parameter))) {
                continue;
            }

            if (($example = $this->examples->get($input)) === null) {
                continue;
            }

            $countries[] = $input;
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
     * @param \Illuminate\Validation\Validator $validator
     * @return bool
     */
    public function validate(string $attribute, $value, array $parameters, Validator $validator): bool
    {
        if ($parameters === []) {
            throw new InvalidArgumentException('Validation rule postal_code_with requires at least 1 parameter.');
        }

        if (!is_string($value)) {
            return false;
        }

        $parameters = Arr::only(Arr::dot($validator->getData()), $parameters);

        if ($parameters === []) {
            return true;
        }

        foreach ($parameters as $parameter) {
            if (!is_string($parameter)) {
                continue;
            }

            if ($this->validator->passes($parameter, $value)) {
                return true;
            }
        }

        return false;
    }
}
