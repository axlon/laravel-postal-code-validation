<?php

declare(strict_types=1);

namespace Axlon\PostalCodeValidation;

use Axlon\PostalCodeValidation\Rules\PostalCodeExtension;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Factory;

final class ValidationServiceProvider extends ServiceProvider
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

        $this->app->singleton('postal_codes', static function () {
            /** @var array<string, string|null> $data */
            $data = require __DIR__ . '/../resources/patterns.php';

            return new PostalCodeValidator($data);
        });

        $this->app->alias('postal_codes', PostalCodeValidator::class);
    }

    /**
     * Register the postal code validation rules with the validator.
     *
     * @param \Illuminate\Validation\Factory $validator
     * @return void
     */
    private function registerRules(Factory $validator): void
    {
        $validator->extendDependent('postal_code', PostalCodeExtension::make(...));
    }
}
