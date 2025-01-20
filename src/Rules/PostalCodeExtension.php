<?php

declare(strict_types=1);

namespace Axlon\PostalCodeValidation\Rules;

use Illuminate\Validation\InvokableValidationRule;
use Illuminate\Validation\Validator;

/**
 * @internal This class is not covered by the backward compatibility promise
 */
final class PostalCodeExtension
{
    /**
     * Call the validation rule as an extension.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array<string> $parameters
     * @param \Illuminate\Validation\Validator $validator
     * @return bool
     */
    public static function make(
        string $attribute,
        mixed $value,
        array $parameters,
        Validator $validator,
    ): bool {
        $rule = InvokableValidationRule::make(PostalCode::of($parameters));
        $rule->setData($validator->getData());
        $rule->setValidator($validator);

        return $rule->passes($attribute, $value);
    }
}
