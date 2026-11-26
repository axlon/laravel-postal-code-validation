<?php

namespace Axlon\PostalCodeValidation;

use Axlon\PostalCodeValidation\Contracts\PostalCodeValidator as PostalCodeValidatorContract;
use Axlon\PostalCodeValidation\Rules\PostalCode;
use Illuminate\Validation\Factory;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\InvokableValidationRule;
use Illuminate\Validation\Validator;
use InvalidArgumentException;

final class PostalCodeServiceProvider extends ServiceProvider
{
    /**
     * Register postal code validation services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->callAfterResolving('validator', static function (Factory $validator) {
            $validator->extendDependent('postal_code', self::validatePostalCode(...));
            $validator->replacer('postal_code', self::replacePostalCode(...));
        });

        $this->app->singleton(PostalCodeValidatorContract::class, static function () {
            /** @var array<string, string|null> $patterns */
            $patterns = require __DIR__ . '/../resources/patterns.php';

            return new PostalCodeValidator($patterns);
        });
    }

    /**
     * Replace all placeholders for the postal_code rule.
     *
     * @param string $message
     * @param string $_attribute
     * @param string $_rule
     * @param array<string> $parameters
     * @param \Illuminate\Validation\Validator $validator
     * @return string
     */
    private static function replacePostalCode(
        string $message,
        string $_attribute,
        string $_rule,
        array $parameters,
        Validator $validator,
    ): string {
        $rule = PostalCode::of($parameters)->setData($validator->getData());
        $areaCodes = $rule->areaCodes();

        return str_replace(':areas', implode(', ', $areaCodes), $message);
    }

    /**
     * Validate that an attribute is a valid postal code.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array<string> $parameters
     * @param \Illuminate\Validation\Validator $validator
     * @return bool
     */
    private static function validatePostalCode(
        string $attribute,
        mixed $value,
        array $parameters,
        Validator $validator,
    ): bool {
        if ($parameters === []) {
            throw new InvalidArgumentException('Validation rule postal_code requires at least 1 parameter.');
        }

        return InvokableValidationRule::make(PostalCode::of($parameters))
            ->setValidator($validator)
            ->passes($attribute, $value);
    }
}
