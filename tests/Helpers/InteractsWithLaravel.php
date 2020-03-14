<?php

namespace Axlon\PostalCodeValidation\Tests\Helpers;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Validation\Factory;
use Mockery;

trait InteractsWithLaravel
{
    /**
     * The Laravel application.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $laravel;

    /**
     * Boot a new Laravel application instance.
     *
     * @return void
     */
    protected function bootLaravel(): void
    {
        $this->laravel = require __DIR__ . '/../../vendor/laravel/laravel/bootstrap/app.php';
        $this->laravel->make(Kernel::class)->bootstrap();
    }

    /**
     * Get the Laravel application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function getLaravel(): Application
    {
        return $this->laravel;
    }

    /**
     * Get a Laravel application instance with a mock validator.
     *
     * @return array
     */
    protected function getLaravelWithMockValidator(): array
    {
        $app = $this->getLaravel();
        $validator = Mockery::mock(Factory::class)->makePartial();

        $app->singleton('validator', function () use ($validator) {
            return $validator;
        });

        return [$app, $validator];
    }

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->bootLaravel();
    }
}
