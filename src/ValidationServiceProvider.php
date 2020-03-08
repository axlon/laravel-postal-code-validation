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
     * @uses \Axlon\PostalCodeValidation\PostalCodeExtension::validatePostalCode()
     * @uses \Axlon\PostalCodeValidation\PostalCodeExtension::validatePostalCodeFor()
     * @uses \Axlon\PostalCodeValidation\PostalCodeReplacer::replacePostalCode()
     * @uses \Axlon\PostalCodeValidation\PostalCodeReplacer::replacePostalCodeFor()
     */
    public function registerRules(Factory $validator): void
    {
        $validator->extend('postal_code', '\Axlon\PostalCodeValidation\PostalCodeExtension@validatePostalCode');
        $validator->replacer('postal_code', 'Axlon\PostalCodeValidation\PostalCodeReplacer@replacePostalCode');
        $validator->replacer('postal_code_for', 'Axlon\PostalCodeValidation\PostalCodeReplacer@replacePostalCodeFor');

        if (method_exists($validator, 'extendDependent')) {
            $validator->extendDependent('postal_code_for', '\Axlon\PostalCodeValidation\PostalCodeExtension@validatePostalCodeFor');
        } else {
            $validator->extend('postal_code_for', '\Axlon\PostalCodeValidation\PostalCodeExtension@validatePostalCodeFor');
        }
    }
}
