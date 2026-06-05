<?php

namespace Axlon\PostalCodeValidation;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Factory;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Register postal code validation services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->callAfterResolving('validator', self::registerRules(...));

        $this->app->singleton('postal_codes', function () {
            return new PostalCodeValidator(require __DIR__ . '/../resources/patterns.php');
        });

        $this->app->alias('postal_codes', PostalCodeValidator::class);
    }

    /**
     * Register the postal code validation rules with the validator.
     *
     * @param \Illuminate\Validation\Factory $validator
     * @return void
     */
    private static function registerRules(Factory $validator): void
    {
        $validator->extend('postal_code', 'Axlon\PostalCodeValidation\Extensions\PostalCode@validate');
        $validator->replacer('postal_code', 'Axlon\PostalCodeValidation\Extensions\PostalCode@replace');

        $validator->replacer('postal_code_for', 'Axlon\PostalCodeValidation\Extensions\PostalCodeFor@replace');
        $validator->replacer('postal_code_with', 'Axlon\PostalCodeValidation\Extensions\PostalCodeFor@replace');

        if (method_exists($validator, 'extendDependent')) {
            $validator->extendDependent('postal_code_for', 'Axlon\PostalCodeValidation\Extensions\PostalCodeFor@validate');
            $validator->extendDependent('postal_code_with', 'Axlon\PostalCodeValidation\Extensions\PostalCodeFor@validate');
        } else {
            $validator->extend('postal_code_for', 'Axlon\PostalCodeValidation\Extensions\PostalCodeFor@validate');
            $validator->extend('postal_code_with', 'Axlon\PostalCodeValidation\Extensions\PostalCodeFor@validate');
        }
    }
}
