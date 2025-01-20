<?php

declare(strict_types=1);

namespace Axlon\PostalCodeValidation;

use Illuminate\Contracts\Validation\Factory;
use Illuminate\Support\ServiceProvider;

final class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Register postal code validation services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->callAfterResolving('validator', function (Factory $validator) {
            $this->registerRules($validator);
        });

        $this->app->singleton('postal_codes', static function () {
            /** @var array<string, string|null> $data */
            $data = require __DIR__ . '/../resources/patterns.php';

            return new PostalCodeValidator($data);
        });

        $this->app->alias('postal_codes', PostalCodeValidator::class);
    }

    /**
     * Register the postal code validation rules with the validator.
     *
     * @param \Illuminate\Contracts\Validation\Factory $validator
     * @return void
     */
    private function registerRules(Factory $validator): void
    {
        $validator->extend('postal_code', 'Axlon\PostalCodeValidation\Extensions\PostalCode@validate');

        if (method_exists($validator, 'extendDependent')) {
            $validator->extendDependent(
                'postal_code_for',
                'Axlon\PostalCodeValidation\Extensions\PostalCodeFor@validate',
            );

            $validator->extendDependent(
                'postal_code_with',
                'Axlon\PostalCodeValidation\Extensions\PostalCodeFor@validate',
            );
        } else {
            $validator->extend('postal_code_for', 'Axlon\PostalCodeValidation\Extensions\PostalCodeFor@validate');
            $validator->extend('postal_code_with', 'Axlon\PostalCodeValidation\Extensions\PostalCodeFor@validate');
        }
    }
}
