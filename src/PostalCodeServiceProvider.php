<?php

declare(strict_types=1);

namespace Axlon\PostalCodeValidation;

use Axlon\PostalCodeValidation\Rules\PostalCode;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Factory;
use Illuminate\Validation\InvokableValidationRule;
use Illuminate\Validation\Validator;

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
            $validator->replacer('postal_code', self::replacePostalCode(...));
            $validator->extendDependent('postal_code', self::validatePostalCode(...));
        });

        $this->app->singleton('postal_codes', static function () {
            /** @var array<string, string|null> $data */
            $data = require __DIR__ . '/../resources/patterns.php';

            return new PostalCodeValidator($data);
        });

        $this->app->alias('postal_codes', PostalCodeValidator::class);
    }

    /**
     * Replace all place-holders for the postal_code rule.
     *
     * @param string $message
     * @param string $attribute
     * @param string $rule
     * @param array<string> $parameters
     * @param \Illuminate\Validation\Validator $validator
     * @return string
     */
    private static function replacePostalCode(
        string $message,
        string $attribute,
        string $rule,
        array $parameters,
        Validator $validator,
    ): string {
        $rule = PostalCode::of($parameters)->setData($validator->getData());
        $regions = implode(', ', $rule->regions());

        return str_replace([':attribute', ':regions'], [$attribute, $regions], $message);
    }

    /**
     * Validate that an attribute is a postal code.
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
        return InvokableValidationRule::make(PostalCode::of($parameters))
            ->setValidator($validator)
            ->passes($attribute, $value);
    }
}
