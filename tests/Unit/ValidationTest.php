<?php

namespace Axlon\PostalCodeValidation\Tests\Unit;

use Illuminate\Contracts\Validation\Factory as FactoryContract;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use PHPUnit\Framework\TestCase;

abstract class ValidationTest extends TestCase
{
    /**
     * @var \Illuminate\Contracts\Validation\Factory
     */
    protected $factory;

    /**
     * Extend the validator.
     *
     * @param \Illuminate\Contracts\Validation\Factory $factory
     * @return void
     */
    abstract protected function extendValidator(FactoryContract $factory);

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $translations = new Translator(
            new FileLoader(new Filesystem(), __DIR__ . '/../resources/lang'), 'en'
        );

        $this->extendValidator(
            $this->factory = new Factory($translations)
        );
    }
}
