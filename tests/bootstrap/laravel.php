<?php

use Axlon\PostalCodeValidation\Tests\Integration\TestCase;
use Axlon\PostalCodeValidation\ValidationServiceProvider;
use Illuminate\Contracts\Console\Kernel;

require_once __DIR__ . '/../../vendor/autoload.php';

if (file_exists(__DIR__ . '/../../vendor/laravel/laravel/artisan')) {
    # If we can find a Laravel installation, we will tell our
    # integration tests to use Laravel when running
    TestCase::resolveUsing(function () {
        $app = require __DIR__ . '/../../vendor/laravel/laravel/bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();
        $app->register(ValidationServiceProvider::class);

        return $app;
    });
} else {
    echo "Cannot find Laravel installation\r\n";
    echo "Please run: composer require laravel/laravel --dev\r\n";
    exit(1);
}
