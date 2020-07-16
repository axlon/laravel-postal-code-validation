<?php

namespace Axlon\PostalCodeValidation\Tests\Integration;

use Closure;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * The application.
     *
     * @var \Illuminate\Foundation\Application|\Laravel\Lumen\Application
     */
    protected $app;

    /**
     * The application resolver.
     *
     * @var \Closure
     */
    protected static $resolver;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->setUpApplication();
    }

    /**
     * Set up a new application instance.
     *
     * @return void
     */
    protected function setUpApplication(): void
    {
        if (!isset(self::$resolver)) {
            $this->markTestSkipped('Application cannot be bootstrapped');
        }

        $this->app = self::$resolver->call($this);
    }

    /**
     * Set the application resolver.
     *
     * @param \Closure $resolver
     * @return void
     */
    public static function resolveUsing(Closure $resolver): void
    {
        self::$resolver = $resolver;
    }
}
