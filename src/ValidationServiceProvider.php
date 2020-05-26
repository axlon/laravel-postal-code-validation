<?php

namespace Axlon\PostalCodeValidation;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Boot postal code validation services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->mergeConfigFrom($configPath = __DIR__ . '/../config/postal_codes.php', 'postal_codes');

        if ($this->app->runningInConsole()) {
            $this->publishes([$configPath => config_path('postal_codes.php')], 'config');
        }
    }

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

        $this->app->singleton('postal_codes', function (Application $app) {
            return new PatternMatcher(
                require __DIR__ . '/../resources/patterns.php', $app['config']['postal_codes.overrides']
            );
        });

        $this->app->alias('postal_codes', PatternMatcher::class);
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
