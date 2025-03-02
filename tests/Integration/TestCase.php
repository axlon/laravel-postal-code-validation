<?php

declare(strict_types=1);

namespace Axlon\PostalCodeValidation\Tests\Integration;

use Axlon\PostalCodeValidation\ValidationServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [ValidationServiceProvider::class];
    }
}
