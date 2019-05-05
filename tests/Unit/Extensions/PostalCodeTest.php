<?php

namespace Axlon\PostalCodeValidation\Tests\Unit\Extensions;

use Axlon\PostalCodeValidation\ValidationServiceProvider;
use Illuminate\Translation\FileLoader;
use Orchestra\Testbench\TestCase;

class PostalCodeTest extends TestCase
{
    /**
     * @var \Illuminate\Contracts\Validation\Factory
     */
    protected $factory;

    /**
     * {@inheritDoc}
     */
    protected function getEnvironmentSetUp($app)
    {
        $app->extend('translation.loader', function () {
            return new FileLoader($this->app['files'], __DIR__ . '/../../resources/lang');
        });
    }

    /**
     * {@inheritDoc}
     */
    protected function getPackageProviders($app)
    {
        return [
            ValidationServiceProvider::class,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->factory = $this->app->make('validator');
    }

    /**
     * Test validation of empty input.
     *
     * @return void
     */
    public function testEmptyInput()
    {
        if (version_compare($this->app->version(), '5.3.0', '<')) {
            # Before Laravel 5.3 nullable was the implicit default
            # See: https://laravel.com/docs/5.3/upgrade#upgrade-5.3.0
            $this->markTestSkipped('Laravel < 5.3 won\'t run validation for empty input');
        }

        $request = ['postal_code' => null];
        $rules = ['postal_code' => 'postal_code:RU'];
        $validator = $this->factory->make($request, $rules);

        $this->assertTrue($validator->fails());
    }

    /**
     * Test validation without parameters.
     *
     * @return void
     */
    public function testEmptyParameterList()
    {
        $request = ['postal_code' => '75008'];
        $rules = ['postal_code' => 'postal_code'];
        $validator = $this->factory->make($request, $rules);

        $this->assertTrue($validator->fails());
    }

    /**
     * Test validation of invalid input.
     *
     * @return void
     */
    public function testValidationOfInvalidInput()
    {
        # Invalid postal code
        $request = ['postal_code' => 'Incorrect postal code'];
        $rules = ['postal_code' => 'postal_code:BE'];
        $validator = $this->factory->make($request, $rules);

        $this->assertTrue($validator->fails());

        # Invalid country code
        $request = ['postal_code' => '75008'];
        $rules = ['postal_code' => 'postal_code:"Incorrect country code"'];
        $validator = $this->factory->make($request, $rules);

        $this->assertTrue($validator->fails());
    }

    /**
     * Test the replacement of error message placeholders.
     *
     * @return void
     * @depends testValidationOfInvalidInput
     */
    public function testErrorMessagePlaceholderReplacement()
    {
        $request = ['postal_code' => 'Incorrect postal code'];
        $rules = ['postal_code' => 'postal_code:CO'];
        $validator = $this->factory->make($request, $rules);

        $this->assertEquals($validator->errors()->get('postal_code'), ['postal code CO ######']);
    }

    /**
     * Test validation.
     *
     * @return void
     */
    public function testValidationOfValidInput()
    {
        $request = ['postal_code' => '1000 AP'];
        $rules = ['postal_code' => 'postal_code:NL'];
        $validator = $this->factory->make($request, $rules);

        $this->assertFalse($validator->fails());
    }
}
