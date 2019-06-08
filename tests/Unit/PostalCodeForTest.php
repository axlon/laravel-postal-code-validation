<?php

namespace Axlon\PostalCodeValidation\Tests\Unit;

use Axlon\PostalCodeValidation\Extensions\PostalCodeFor;
use Axlon\PostalCodeValidation\Validator;
use Illuminate\Contracts\Validation\Factory as FactoryContract;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class PostalCodeForTest extends ValidationTest
{
    /**
     * {@inheritDoc}
     */
    protected function extendValidator(FactoryContract $factory)
    {
        $rule = new PostalCodeFor(new Request, new Validator);

        $factory->extend('postal_code_for', function (...$parameters) use ($rule) {
            return $rule->validate(...$parameters);
        });

        $factory->replacer('postal_code_for', function (...$parameters) use ($rule) {
            return $rule->replace(...$parameters);
        });
    }

    /**
     * Test validation of empty input.
     *
     * @return void
     */
    public function testEmptyInput()
    {
        if (version_compare(Application::VERSION, '5.3.0', '<')) {
            # Before Laravel 5.3 nullable was the implicit default
            # See: https://laravel.com/docs/5.3/upgrade#upgrade-5.3.0
            $this->markTestSkipped('Laravel < 5.3 won\'t run validation for empty input');
        }

        $request = ['country' => 'RU', 'postal_code' => null];
        $rules = ['postal_code' => 'postal_code_for:country'];
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
        $rules = ['postal_code' => 'postal_code_for'];
        $validator = $this->factory->make($request, $rules);

        $this->assertFalse($validator->fails());
    }

    /**
     * Test validation of invalid input.
     *
     * @return void
     */
    public function testValidationOfInvalidInput()
    {
        # Invalid postal code
        $request = ['country' => 'BE', 'postal_code' => 'Incorrect postal code'];
        $rules = ['postal_code' => 'postal_code_for:country'];
        $validator = $this->factory->make($request, $rules);

        $this->assertTrue($validator->fails());

        # Invalid country code
        $request = ['country' => 'Incorrect country code', 'postal_code' => '75008'];
        $rules = ['postal_code' => 'postal_code_for:country'];
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
        $request = ['country' => 'CO', 'postal_code' => 'Incorrect postal code'];
        $rules = ['postal_code' => 'postal_code_for:country'];
        $validator = $this->factory->make($request, $rules);

        $this->assertEquals(['postal code CO ######'], $validator->errors()->get('postal_code'));
    }

    /**
     * Test validation.
     *
     * @return void
     */
    public function testValidationOfValidInput()
    {
        $request = ['country' => 'NL', 'postal_code' => '1000 AP'];
        $rules = ['postal_code' => 'postal_code_for:country'];
        $validator = $this->factory->make($request, $rules);

        $this->assertFalse($validator->fails());
    }
}
