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
        if ($this->app->resolved('validator')) {
            $this->registerRules($this->app['validator']);
        } else {
            $this->app->resolving('validator', function (Factory $validator) {
                $this->registerRules($validator);
            });
        }

        $this->app->singleton('postal_codes', static function () {
            return new PostalCodeValidator(require __DIR__ . '/../resources/patterns.php');
        });

        $this->app->alias('postal_codes', PostalCodeValidator::class);
    }

    /**
     * Register the postal code validation rules with the validator.
     *
     * @param \Illuminate\Contracts\Validation\Factory $validator
     * @return void
     */
    public function registerRules(Factory $validator): void
    {
        $validator->extend('postal_code', 'Axlon\PostalCodeValidation\Extensions\PostalCode@validate');
        $validator->replacer('postal_code', 'Axlon\PostalCodeValidation\Extensions\PostalCode@replace');

        $validator->replacer('postal_code_for', 'Axlon\PostalCodeValidation\Extensions\PostalCodeFor@replace');
        $validator->replacer('postal_code_with', 'Axlon\PostalCodeValidation\Extensions\PostalCodeFor@replace');

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
