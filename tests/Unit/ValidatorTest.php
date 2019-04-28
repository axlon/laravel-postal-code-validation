<?php

namespace Axlon\PostalCodeValidation\Tests\Unit;

use Axlon\PostalCodeValidation\Validator;
use InvalidArgumentException;
use Orchestra\Testbench\TestCase;

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
            'Canada' => ['CA'],
            'Ghana' => ['GH'],
            'North Korea' => ['KP'],
            'Saint Martin, lowercase' => ['mf'],
            'Sweden, titlecase' => ['Se'],
            'South Korea' => ['KR'],
        ];
    }

    /**
     * Provide formats.
     *
     * @return array
     */
    public function provideFormats()
    {
        return [
            'India' => ['IN', ['######', '### ###']],
            'Saint Helena, lowercase' => ['sh', ['@@@@ 1ZZ']],
            'Sudan, titlecase' => ['Sd', ['#####']],
            'Suriname' => ['SR', []],
            'Taiwan' => ['TW', ['###', '###-##']],
        ];
    }

    /**
     * Provide regex patterns.
     *
     * @return array
     */
    public function providePatterns()
    {
        return [
            'Jamaica' => ['JM', '/\d{2}/i'],
            'Papua New Guinea' => ['PG', '/\d{3}/i'],
            'Qatar, lowercase' => ['qa', '/.*/'],
            'Turks and Caicos Islands' => ['TC', '/TKCA\s?1ZZ/i'],
            'Venezuela, titlecase' => ['Ve', '/\d{4}|\d{4}-[a-z]/i'],
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
            'China' => ['CH', '736200'],
            'France' => ['FR', '67290'],
            'Germany' => ['DE', '49084'],
            'Great Britain' => ['GB', 'EX1 2NZ'],
            'Great Britain, no space' => ['GB', 'EX12NZ'],
            'Ireland' => ['IE', 'D02 AF30'],
            'Ireland, no space' => ['IE', 'D02AF30'],
            'Japan' => ['JP', '196-0000'],
            'Japan, no hyphen' => ['JP', '1960000'],
            'Nairu' => ['NR', 'Literally anything'],
            'Moldova' => ['MD', 'MD-2001'],
            'Moldova, no hyphen' => ['MD', 'MD2001'],
            'Netherlands' => ['NL', '2393 ER'],
            'Netherlands, no space' => ['NL', '2393ER'],
            'Vatican' => ['VA', '00120'],
            'United States' => ['US', '99501'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->validator = new Validator();
    }

    /**
     * Test formats of an invalid country code.
     *
     * @return void
     */
    public function testFormatsOfInvalidCountryCodes()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->validator->getFormats('XX');
    }

    /**
     * Test formats retrieval.
     *
     * @param string $countryCode
     * @param array $formats
     * @return void
     * @dataProvider provideFormats
     */
    public function testFormats(string $countryCode, array $formats)
    {
        $this->assertEquals($formats, $this->validator->getFormats($countryCode));
    }

    /**
     * Test compiled patterns of an invalid country code.
     *
     * @return void
     */
    public function testPatternOfInvalidCountryCodes()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->validator->getPattern('XX');
    }

    /**
     * Test compiled patterns.
     *
     * @param string $countryCode
     * @param string $pattern
     * @return void
     * @dataProvider providePatterns
     */
    public function testPatterns(string $countryCode, string $pattern)
    {
        $this->assertEquals($pattern, $this->validator->getPattern($countryCode));
    }

    /**
     * Test validation, when an invalid country is passed.
     *
     * @return void
     */
    public function testValidationWithInvalidCountryCodes()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->assertFalse($this->validator->supports('XX'));
        $this->validator->isValid('XX', '02305');
    }

    /**
     * Test validation, when an invalid postal code is passed.
     *
     * @return void
     */
    public function testValidationWithInvalidPostalCodes()
    {
        $this->assertTrue($this->validator->supports('CO'));
        $this->assertFalse($this->validator->isValid('CO', 'Not a postal code'));
    }

    /**
     * Test validation of valid postal codes.
     *
     * @param string $countryCode
     * @param string $postalCode
     * @return void
     * @dataProvider providePostalCodes
     */
    public function testValidPostalCodes(string $countryCode, string $postalCode)
    {
        $this->assertTrue($this->validator->isValid($countryCode, $postalCode));
    }
}
