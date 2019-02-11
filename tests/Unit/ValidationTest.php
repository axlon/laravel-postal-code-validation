<?php

namespace Axlon\PostalCodeValidation\Tests\Unit;

use Axlon\PostalCodeValidation\Rules\PostalCode;
use Axlon\PostalCodeValidation\ValidationServiceProvider;
use Exception;
use InvalidArgumentException;
use Orchestra\Testbench\TestCase;

class ValidationTest extends TestCase
{
    /**
     * The framework validation factory.
     *
     * @var \Illuminate\Contracts\Validation\Factory
     */
    protected $factory;

    /**
     * Get package providers.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return string[]
     */
    protected function getPackageProviders($app)
    {
        return [ValidationServiceProvider::class];
    }

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->factory = $this->app->make('validator');
    }

    /**
     * Test if country codes are correctly validated from other input values.
     *
     * @return void
     */
    public function testCountryCodeFromRequest()
    {
        $request = ['country' => 'IT', 'postal_code' => '23100'];
        $rules = ['country' => 'string|size:2|required', 'postal_code' => 'postal_code:country'];
        $validator = $this->factory->make($request, $rules);

        $this->assertFalse($validator->fails());
    }

    /**
     * Test if validation still passes when a country code with incorrect casing is passed.
     *
     * @return void
     */
    public function testCountryCodeWithIncorrectCasing()
    {
        $request = ['postal_code' => '12345'];
        $rules = ['postal_code' => 'postal_code:de'];
        $validator = $this->factory->make($request, $rules);

        $this->assertFalse($validator->fails());
    }

    /**
     * Test if validation fails when an empty country code is passed.
     *
     * @return void
     */
    public function testEmptyCountryCode()
    {
        $request = ['postal_code' => '75008'];
        $rules = ['postal_code' => 'postal_code'];
        $validator = $this->factory->make($request, $rules);

        $this->assertTrue($validator->fails());
    }

    /**
     * Test if validation fails when an empty postal code is passed.
     *
     * @return void
     */
    public function testEmptyPostalCode()
    {
        $request = ['postal_code' => null];
        $rules = ['postal_code' => 'postal_code:RU'];
        $validator = $this->factory->make($request, $rules);

        $this->assertTrue($validator->fails());
    }

    /**
     * Test if validation fails when an invalid country code is passed.
     *
     * @return void
     */
    public function testInvalidCountryCode()
    {
        $exception = null;
        $request = ['postal_code' => '75008'];
        $rules = ['postal_code' => 'postal_code:FOO'];
        $validator = $this->factory->make($request, $rules);

        try {
            $validator->fails();
        } catch (Exception $exception) {
            # Do nothing here..
        }

        # Manually assert exception to maintain BC with older versions of PHPUnit
        $this->assertInstanceOf(InvalidArgumentException::class, $exception);
    }

    /**
     * Test if validation fails when an invalid postal code is passed.
     *
     * @return void
     */
    public function testInvalidPostalCode()
    {
        $request = ['postal_code' => 'Some arbitrary string'];
        $rules = ['postal_code' => 'postal_code:BE'];
        $validator = $this->factory->make($request, $rules);

        $this->assertTrue($validator->fails());
    }

    /**
     * Test if validation passes if a valid postal code is passed.
     *
     * @return void
     */
    public function testValidPostalCode()
    {
        $request = ['postal_code' => '1000 AP'];
        $rules = ['postal_code' => 'postal_code:NL'];
        $validator = $this->factory->make($request, $rules);

        $this->assertFalse($validator->fails());
    }

    /**
     * Test if validation passes if object notation is used.
     *
     * @return void
     */
    public function testValidationUsingObjectNotation()
    {
        $request = ['postal_code' => '28770'];
        $rules = ['postal_code' => [PostalCode::forCountry('ES')]];
        $validator = $this->factory->make($request, $rules);

        $this->assertFalse($validator->fails());
    }
}
