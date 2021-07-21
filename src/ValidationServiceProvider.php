<?php

namespace Axlon\PostalCodeValidation;

use Axlon\PostalCodeValidation\Contracts\Ruleset;
use Axlon\PostalCodeValidation\Rules\ISO3166_1\Alpha2;
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
        if ($this->app->resolved('validator')) {
            $this->registerRules($validator = $this->app['validator']);
            $this->registerReplacers($validator);
        } else {
            $this->app->resolving('validator', function (Factory $validator) {
                $this->registerReplacers($validator);
                $this->registerRules($validator);
            });
        }

        $this->app->bindIf('postal_codes', static function () {
            return new Alpha2(static function () {
                return require __DIR__ . '/../resources/rules.php';
            });
        }, true);

        $this->app->alias('postal_codes', Ruleset::class);
    }

    /**
     * Register the error message replacers.
     *
     * @param \Illuminate\Validation\Factory $validator
     * @return void
     */
    private function registerReplacers(Factory $validator): void
    {
        $validator->replacer(
            'postal_code',
            'Axlon\PostalCodeValidation\PostalCodeValidator@replacePostalCode',
        );

        $validator->replacer(
            'postal_code_with',
            'Axlon\PostalCodeValidation\PostalCodeValidator@replacePostalCodeWith',
        );
    }

    /**
     * Register the validation rules.
     *
     * @param \Illuminate\Validation\Factory $validator
     * @return void
     */
    private function registerRules(Factory $validator): void
    {
        $validator->extend(
            'postal_code',
            'Axlon\PostalCodeValidation\PostalCodeValidator@validatePostalCode',
            'The :attribute must be a valid postal code.',
        );

        $validator->extendDependent(
            'postal_code_with',
            'Axlon\PostalCodeValidation\PostalCodeValidator@validatePostalCodeWith',
            'The :attribute must be a valid postal code.',
        );
    }
}
