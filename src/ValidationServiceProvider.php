<?php

declare(strict_types=1);

namespace Axlon\PostalCodeValidation;

use Axlon\PostalCodeValidation\Contracts\ConstraintRepository;
use Axlon\PostalCodeValidation\Contracts\ExampleRepository;
use Axlon\PostalCodeValidation\Support\PostalCodeExamples;
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
        $this->app->singleton(ConstraintRepository::class, static function () {
            /** @var array<string, string|null> $data */
            $data = require __DIR__ . '/../resources/patterns.php';

            return new PostalCodeValidator($data);
        });

        $this->app->singleton(ExampleRepository::class, PostalCodeExamples::class);

        $this->callAfterResolving('validator', self::registerRules(...));
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

        $validator->replacer('postal_code_with', 'Axlon\PostalCodeValidation\Extensions\PostalCodeFor@replace');
        $validator->extendDependent('postal_code_with', 'Axlon\PostalCodeValidation\Extensions\PostalCodeFor@validate');
    }
}
