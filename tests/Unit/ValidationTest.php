<?php

namespace Axlon\PostalCodeValidation\Tests\Unit;

use Axlon\PostalCodeValidation\Rules\PostalCode;
use Axlon\PostalCodeValidation\ValidationServiceProvider;
use Exception;
use Illuminate\Contracts\Validation\Factory;
use InvalidArgumentException;
use Orchestra\Testbench\TestCase;

class ValidationTest extends TestCase
{
    /** @var Factory */
    protected $factory;

    protected function getPackageProviders($app)
    {
        return [ValidationServiceProvider::class];
    }

    public function setUp()
    {
        parent::setUp();
        $this->factory = $this->app->make('validator');
    }

    public function testCountryCodeWithIncorrectCasing()
    {
        $request = ['postal_code' => '12345'];
        $rules = ['postal_code' => 'postal_code:de'];
        $validator = $this->factory->make($request, $rules);

        $this->assertFalse($validator->fails());
    }

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

    public function testInvalidPostalCode()
    {
        $request = ['postal_code' => 'Some arbitrary string'];
        $rules = ['postal_code' => 'postal_code:BE'];
        $validator = $this->factory->make($request, $rules);

        $this->assertTrue($validator->fails());
    }

    public function testValidPostalCode()
    {
        $request = ['postal_code' => '1000 AP'];
        $rules = ['postal_code' => 'postal_code:NL'];
        $validator = $this->factory->make($request, $rules);

        $this->assertFalse($validator->fails());
    }

    public function testValidationUsingObjectNotation()
    {
        $request = ['postal_code' => '28770'];
        $rules = ['postal_code' => [PostalCode::forCountry('ES')]];
        $validator = $this->factory->make($request, $rules);

        $this->assertFalse($validator->fails());
    }
}
