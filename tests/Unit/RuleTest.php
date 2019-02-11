<?php

namespace Axlon\PostalCodeValidation\Tests\Unit;

use Axlon\PostalCodeValidation\Rules\PostalCode;
use Orchestra\Testbench\TestCase;

class RuleTest extends TestCase
{
    /**
     * Test if rule objects are converted to a correct validation string.
     *
     * @return void
     */
    public function testRuleToValidationStringConversion()
    {
        $this->assertEquals('postal_code:ES', (string)PostalCode::forCountry('ES'));
        $this->assertEquals('postal_code:AF,GH', (string)PostalCode::forCountry('AF')->andCountry('GH'));
    }
}
