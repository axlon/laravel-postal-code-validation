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
    public function register()
    {
        $this->app->singleton('Axlon\PostalCodeValidation\Validator');
        $this->app->singleton('Axlon\PostalCodeValidation\Extensions\PostalCodeFor');

        $this->app->afterResolving('validator', function (Factory $factory) {
            $factory->extend('postal_code', 'Axlon\PostalCodeValidation\Extensions\PostalCode@validate');
            $factory->replacer('postal_code', 'Axlon\PostalCodeValidation\Extensions\PostalCode@replace');

            if (method_exists($factory, 'extendDependent')) {
                $factory->extendDependent('postal_code_for', 'Axlon\PostalCodeValidation\Extensions\PostalCodeFor@validate');
                $factory->replacer('postal_code_for', 'Axlon\PostalCodeValidation\Extensions\PostalCodeFor@replace');
            } else {
                $factory->extend('postal_code_for', 'Axlon\PostalCodeValidation\Extensions\PostalCodeFor@validate');
                $factory->replacer('postal_code_for', 'Axlon\PostalCodeValidation\Extensions\PostalCodeFor@replace');
            }
        });
    }
}
