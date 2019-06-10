<?php

namespace Axlon\PostalCodeValidation\Tests\Unit;

use Axlon\PostalCodeValidation\Validator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    /**
     * @var \Axlon\PostalCodeValidation\Validator
     */
    protected $validator;

    /**
     * Provide country codes.
     *
     * @return array
     */
    public function provideCountryCodes()
    {
        return [
            'Belgium' => ['BE'],
            'China' => ['CH'],
            'France' => ['FR'],
            'Germany' => ['DE'],
            'Great Britain' => ['GB'],
            'Ireland' => ['IE'],
            'Japan' => ['JP'],
            'Nairu' => ['NR'],
            'Moldova' => ['MD'],
            'Netherlands' => ['NL'],
            'Vatican' => ['VA'],
            'United States' => ['US'],
        ];
    }

    /**
     * Provide postal codes.
     *
     * @return array
     */
    public function providePostalCodes()
    {
        return [
            'Belgium' => ['BE', '1620'],
            'China' => ['CH', '3012'],
            'France' => ['FR', '67290'],
            'Germany' => ['DE', '49084'],
            'Great Britain' => ['GB', 'EX1 2NZ'],
            'Great Britain, no space' => ['GB', 'EX12NZ'],
            'Ireland' => ['IE', 'D02 AF30'],
            'Ireland, no space' => ['IE', 'D02AF30'],
            'Japan' => ['JP', '196-0000'],
            'Nairu' => ['NR', 'Literally anything'],
            'Moldova' => ['MD', '2001'],
            'Netherlands' => ['NL', '2393 ER'],
            'Netherlands, no space' => ['NL', '2393ER'],
            'Vatican' => ['VA', '00120'],
            'United States' => ['US', '99501'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $this->validator = new Validator();
    }

    /**
     * Test the generated example postal codes.
     *
     * @param string $countryCode
     * @return void
     * @dataProvider provideCountryCodes
     */
    public function testExamples(string $countryCode)
    {
        $this->assertTrue($this->validator->supports($countryCode));

        if (is_null($example = $this->validator->getExample($countryCode))) {
            return;
        }

        $this->assertTrue($this->validator->validate($countryCode, $example));
    }

    /**
     * Test validation of valid postal codes.
     *
     * @param string $countryCode
     * @param string $postalCode
     * @return void
     * @dataProvider providePostalCodes
     */
    public function testValidation(string $countryCode, string $postalCode)
    {
        $this->assertTrue($this->validator->validate($countryCode, $postalCode));
    }

    /**
     * Test what happens if an non-existent country code is used for validation.
     *
     * @return void
     */
    public function testValidationWithUnsupportedCountry()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported country code XX');

        $this->assertFalse($this->validator->supports('XX'));
        $this->validator->validate('XX', '0000');
    }
}
