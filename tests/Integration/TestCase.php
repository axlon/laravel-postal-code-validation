<?php

namespace Tests\Integration;

use Axlon\PostalCodeValidation\ValidationServiceProvider;
use Orchestra\Testbench\TestCase as TestbenchTestCase;

abstract class TestCase extends TestbenchTestCase
{
    protected function getPackageProviders($app): array
    {
        return [ValidationServiceProvider::class];
    }
}
