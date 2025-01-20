<?php

declare(strict_types=1);

namespace Tests;

use Axlon\PostalCodeValidation\ValidationServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Get the package providers.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return list<class-string<\Illuminate\Support\ServiceProvider>>
     */
    protected function getPackageProviders($app): array
    {
        return [
            ValidationServiceProvider::class,
        ];
    }
}
