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
        $this->app->afterResolving('validator', function (Factory $factory) {
            $factory->extend('postal_code', 'Axlon\PostalCodeValidation\Validator@validate');
        });
    }
}
