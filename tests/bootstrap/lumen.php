<?php

use Axlon\PostalCodeValidation\Tests\Integration\TestCase;
use Axlon\PostalCodeValidation\ValidationServiceProvider;
use Laravel\Lumen\Application;

require_once __DIR__ . '/../../vendor/autoload.php';

if (file_exists(__DIR__ . '/../../vendor/laravel/lumen/artisan')) {
    # If we can find a Lumen installation, we will tell our
    # integration tests to use Lumen when running
    TestCase::resolveUsing(function () {
        $app = new Application();
        $app->configure('app');

        if (is_callable([$app, 'boot'])) {
            $app->boot();
        }

        $app->withFacades();
        $app->register(ValidationServiceProvider::class);

        return $app;
    });
} else {
    echo "Cannot find Lumen installation\r\n";
    echo "Please run: composer require laravel/lumen --dev\r\n";
    exit(1);
}
