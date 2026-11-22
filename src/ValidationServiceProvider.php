<?php

namespace Axlon\PostalCodeValidation;

use Illuminate\Validation\Factory;
use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
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

        $this->app->singleton(PostalCodeValidator::class, static function () {
            /** @var array<string, string|null> $patterns */
            $patterns = require __DIR__ . '/../resources/patterns.php';

            return new PostalCodeValidator($patterns);
        });
    }

    /**
     * Register the postal code validation rules with the validator.
     *
     * @param \Illuminate\Validation\Factory $validator
     * @return void
     */
    private function registerRules(Factory $validator): void
    {
        $validator->extend('postal_code', 'Axlon\PostalCodeValidation\Extensions\PostalCode@validate');
        $validator->replacer('postal_code', 'Axlon\PostalCodeValidation\Extensions\PostalCode@replace');

        $validator->replacer('postal_code_for', 'Axlon\PostalCodeValidation\Extensions\PostalCodeFor@replace');
        $validator->replacer('postal_code_with', 'Axlon\PostalCodeValidation\Extensions\PostalCodeFor@replace');

        $validator->extendDependent('postal_code_for', 'Axlon\PostalCodeValidation\Extensions\PostalCodeFor@validate');
        $validator->extendDependent('postal_code_with', 'Axlon\PostalCodeValidation\Extensions\PostalCodeFor@validate');
    }
}
