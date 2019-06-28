<?php

namespace Axlon\PostalCodeValidation\Tests\Unit;

use Axlon\PostalCodeValidation\Extensions\PostalCode;
use Axlon\PostalCodeValidation\Validator;

class PostalCodeValidationTest extends ValidationTest
{
    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $extension = new PostalCode(new Validator());

        $this->getFactory()->extend('postal_code', function (...$parameters) use ($extension) {
            return $extension->validate(...$parameters);
        });
    }

    /**
     * Test validation of empty input.
     *
     * @return void
     */
    public function testEmptyInput()
    {
        if (version_compare($this->getLaravelVersion(), '5.3.0', '<')) {
            # Before Laravel 5.3 nullable was the implicit default
            # See: https://laravel.com/docs/5.3/upgrade#upgrade-5.3.0
            $this->markTestSkipped('Laravel < 5.3 won\'t run validation for empty input');
        }

        $request = ['postal_code' => null];
        $rules = ['postal_code' => 'postal_code:RU'];

        $this->assertFails($request, $rules);
    }

    /**
     * Test validation without any parameters.
     *
     * @return void
     */
    public function testEmptyParameterList()
    {
        $request = ['postal_code' => '75008'];
        $rules = ['postal_code' => 'postal_code'];

        $this->assertPasses($request, $rules);
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

        $this->assertFails($request, $rules);

        # Invalid country code
        $request = ['postal_code' => '75008'];
        $rules = ['postal_code' => 'postal_code:"Incorrect country code"'];

        $this->assertFails($request, $rules);
    }

    /**
     * Test validation of valid input.
     *
     * @return void
     */
    public function testValidationOfValidInput()
    {
        $request = ['postal_code' => '1234 AB'];
        $rules = ['postal_code' => 'postal_code:NL'];

        $this->assertPasses($request, $rules);
    }
}
