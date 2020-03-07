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

        $this->getFactory()->replacer('postal_code', function (...$parameters) use ($extension) {
            return $extension->replace(...$parameters);
        });
    }

    /**
     * Test validation of empty input.
     *
     * @return void
     */
    public function testEmptyInput()
    {
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
     * Test the error message given by the validation system.
     *
     * @return void
     */
    public function testErrorMessage()
    {
        $request = ['postal_code' => 'Incorrect postal code'];
        $rules = ['postal_code' => 'postal_code:PL'];
        $validator = $this->getFactory()->make($request, $rules);

        $this->assertContains(
            'The postal code field must be a valid PL postal code (e.g. 00-950).',
            $validator->getMessageBag()->all()
        );
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
