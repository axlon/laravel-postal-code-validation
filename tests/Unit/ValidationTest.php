<?php

namespace Axlon\PostalCodeValidation\Tests\Unit;

use Axlon\PostalCodeValidation\Rules\PostalCode;
use Axlon\PostalCodeValidation\ValidationServiceProvider;
use Orchestra\Testbench\TestCase;

class ValidationTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [ValidationServiceProvider::class];
    }

    public function testInvalidPostalCode()
    {
        $request = ['postal_code' => 'Some arbitrary string'];
        $rulesAsObject = ['postal_code' => [PostalCode::forCountry('NL')]];
        $rulesAsString = ['postal_code' => 'postal_code:NL'];
        $validator = $this->app->make('validator');

        $this->assertTrue($validator->make($request, $rulesAsObject)->fails());
        $this->assertTrue($validator->make($request, $rulesAsString)->fails());
    }

    public function testValidPostalCode()
    {
        $request = ['postal_code' => '1000 AA'];
        $rulesAsObject = ['postal_code' => [PostalCode::forCountry('NL')]];
        $rulesAsString = ['postal_code' => 'postal_code:NL'];
        $validator = $this->app->make('validator');

        $this->assertTrue($validator->make($request, $rulesAsObject)->passes());
        $this->assertTrue($validator->make($request, $rulesAsString)->passes());
    }
}
