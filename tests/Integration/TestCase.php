<?php

namespace Axlon\PostalCodeValidation\Tests\Integration;

use Axlon\PostalCodeValidation\ValidationServiceProvider;
use Closure;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [ValidationServiceProvider::class];
    }
}
