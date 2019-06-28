<?php

namespace Axlon\PostalCodeValidation\Tests\Unit;

use Axlon\PostalCodeValidation\Tests\Constraints\Fails;
use Axlon\PostalCodeValidation\Tests\Constraints\Passes;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use PackageVersions\Versions;
use PHPUnit\Framework\TestCase;

abstract class ValidationTest extends TestCase
{
    /**
     * @var \Illuminate\Contracts\Validation\Factory
     */
    protected $factory;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->factory = new Factory(new Translator(
            new FileLoader(new Filesystem(), __DIR__), 'en'
        ));
    }

    /**
     * Assert that given request data fails validation.
     *
     * @param array $request
     * @param array $rules
     * @return void
     */
    public function assertFails(array $request, array $rules)
    {
        static::assertThat($request, new Fails($this->factory, $rules));
    }

    /**
     * Assert that given request data passes validation.
     *
     * @param array $request
     * @param array $rules
     * @return void.
     */
    public function assertPasses(array $request, array $rules)
    {
        static::assertThat($request, new Passes($this->factory, $rules));
    }

    /**
     * Get the validation factory.
     *
     * @return \Illuminate\Contracts\Validation\Factory
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * Get the Laravel version.
     *
     * @return string
     */
    public function getLaravelVersion()
    {
        return ltrim(explode('@', Versions::getVersion('illuminate/validation'))[0], 'v');
    }
}
