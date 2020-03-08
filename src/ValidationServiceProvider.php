<?php

namespace Axlon\PostalCodeValidation;

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

        $this->app->singleton(PostalCodeValidator::class);
    }

    /**
     * Register the postal code validation rules with the validator.
     *
     * @param \Illuminate\Contracts\Validation\Factory $validator
     * @return void
     * @uses \Axlon\PostalCodeValidation\ValidationExtension::validatePostalCode()
     * @uses \Axlon\PostalCodeValidation\ValidationExtension::validatePostalCodeFor()
     * @uses \Axlon\PostalCodeValidation\Replacer::replacePostalCode()
     * @uses \Axlon\PostalCodeValidation\Replacer::replacePostalCodeFor()
     */
    public function registerRules(Factory $validator): void
    {
        $validator->extend('postal_code', '\Axlon\PostalCodeValidation\ValidationExtension@validatePostalCode');
        $validator->replacer('postal_code', 'Axlon\PostalCodeValidation\Replacer@replacePostalCode');
        $validator->replacer('postal_code_for', 'Axlon\PostalCodeValidation\Replacer@replacePostalCodeFor');

        if (method_exists($validator, 'extendDependent')) {
            $validator->extendDependent('postal_code_for', '\Axlon\PostalCodeValidation\ValidationExtension@validatePostalCodeFor');
        } else {
            $validator->extend('postal_code_for', '\Axlon\PostalCodeValidation\ValidationExtension@validatePostalCodeFor');
        }
    }
}
