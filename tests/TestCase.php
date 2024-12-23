<?php

namespace Tests;

use Axlon\PostalCodeValidation\ValidationServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Get package providers.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            ValidationServiceProvider::class,
        ];
    }
}
