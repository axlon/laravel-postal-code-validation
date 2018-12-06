<?php

namespace Axlon\PostalCodeValidation\Tests\Unit;

use Axlon\PostalCodeValidation\Rules\PostalCode;
use Axlon\PostalCodeValidation\ValidationServiceProvider;
use Illuminate\Validation\ValidationException;
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
        $rulesAsString = ['postal_code' => 'postal_code:NL'];
        $validator = $this->app->make('validator');

        $this->expectException(ValidationException::class);
        $validator->validate($request, $rulesAsString);
    }

    public function testValidPostalCode()
    {
        $request = ['postal_code' => '1000 AA'];
        $rulesAsObject = ['postal_code' => [PostalCode::forCountry('NL')]];
        $rulesAsString = ['postal_code' => 'postal_code:NL'];
        $validator = $this->app->make('validator');

        $this->assertCount(1, $validator->validate($request, $rulesAsObject));
        $this->assertCount(1, $validator->validate($request, $rulesAsString));
    }
}
