<?php

namespace Axlon\PostalCodeValidation;

use Axlon\PostalCodeValidation\Extensions\PostalCodeFor;
use Illuminate\Contracts\Validation\Factory;
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
        if ($this->app->resolved('validator')) {
            $this->registerRules($this->app['validator']);
        } else {
            $this->app->resolving('validator', function (Factory $validator) {
                $this->registerRules($validator);
            });
        }

        $this->app->singleton(PostalCodeFor::class);
        $this->app->singleton(Validator::class);
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

        if (method_exists($validator, 'extendDependent')) {
            $validator->extendDependent('postal_code_for', 'Axlon\PostalCodeValidation\Extensions\PostalCodeFor@validate');
            $validator->replacer('postal_code_for', 'Axlon\PostalCodeValidation\Extensions\PostalCodeFor@replace');
        } else {
            $validator->extend('postal_code_for', 'Axlon\PostalCodeValidation\Extensions\PostalCodeFor@validate');
            $validator->replacer('postal_code_for', 'Axlon\PostalCodeValidation\Extensions\PostalCodeFor@replace');
        }
    }
}
