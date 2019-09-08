<?php

namespace Axlon\PostalCodeValidation\Tests\Unit;

use Axlon\PostalCodeValidation\Extensions\PostalCodeFor;
use Axlon\PostalCodeValidation\Validator;
use Illuminate\Http\Request;

class PostalCodeForValidationTest extends ValidationTest
{
    /**
     * The extension strategy.
     *
     * @var string
     */
    protected $strategy;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $extension = new PostalCodeFor(new Request(), new Validator());
        $this->strategy = method_exists($this->getFactory(), 'extendDependent') ? 'extendDependent' : 'extend';

        $this->getFactory()->{$this->strategy}('postal_code_for', function (...$parameters) use ($extension) {
            return $extension->validate(...$parameters);
        });

        $this->getFactory()->replacer('postal_code_for', function (...$parameters) use ($extension) {
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
        if (version_compare($this->getLaravelVersion(), '5.3.0', '<')) {
            # Before Laravel 5.3 nullable was the implicit default
            # See: https://laravel.com/docs/5.3/upgrade#upgrade-5.3.0
            $this->markTestSkipped('Laravel < 5.3 won\'t run validation for empty input');
        }

        $request = ['postal_code' => null, 'country' => 'RU'];
        $rules = ['postal_code' => 'postal_code_for:country'];

        $this->assertFails($request, $rules);
    }

    /**
     * Test the error message given by the validation system.
     *
     * @return void
     */
    public function testErrorMessage()
    {
        $request = ['country' => 'PL', 'postal_code' => 'Incorrect postal code'];
        $rules = ['postal_code' => 'postal_code_for:country'];
        $validator = $this->getFactory()->make($request, $rules);

        $this->assertContains(
            'The postal code field must be a valid PL postal code (e.g. 00-950).',
            $validator->getMessageBag()->all()
        );
    }

    /**
     * Test validation without any parameters.
     *
     * @return void
     */
    public function testEmptyParameterList()
    {
        $request = ['postal_code' => '75008'];
        $rules = ['postal_code' => 'postal_code_for'];

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
        $request = ['postal_code' => 'Incorrect postal code', 'country' => 'BE'];
        $rules = ['postal_code' => 'postal_code_for:country'];

        $this->assertFails($request, $rules);

        # Invalid country code
        $request = ['postal_code' => '75008', 'country' => 'Incorrect country code'];
        $rules = ['postal_code' => 'postal_code_for:country'];

        $this->assertFails($request, $rules);
    }

    /**
     * Test validation of valid input.
     *
     * @return void
     */
    public function testValidationOfValidInput()
    {
        # Regular input
        $request = ['postal_code' => '1234 AB', 'country' => 'NL'];
        $rules = ['postal_code' => 'postal_code_for:country'];

        $this->assertPasses($request, $rules);

        # Country not sent along
        $request = ['postal_code' => '10000'];
        $rules = ['postal_code' => 'postal_code_for:country'];

        $this->assertPasses($request, $rules);

        # Empty country
        $request = ['postal_code' => '12500', 'country' => ''];
        $rules = ['postal_code' => 'postal_code_for:country'];

        $this->assertPasses($request, $rules);
    }

    /**
     * Test validation of valid input, using asterisks.
     *
     * @return void
     */
    public function testValidAsterisksValidation()
    {
        if ($this->strategy !== 'extendDependent') {
            $this->markTestSkipped('Dependent validation rules not supported');
        }

        # Multiple valid postal codes
        $request = ['countries' => ['NZ', 'RS'], 'postal_codes' => ['6001', '106314']];
        $rules = ['postal_codes.*' => 'postal_code_for:countries.*'];

        $this->assertPasses($request, $rules);

        # More postal codes than countries
        $request = ['countries' => ['CA'], 'postal_codes' => ['H3Z 2Y7', '6799']];
        $rules = ['postal_codes.*' => 'postal_code_for:countries.*'];

        $this->assertPasses($request, $rules);

        # More countries than postal codes
        $request = ['countries' => ['IL', 'IO'], 'postal_codes' => ['9614303']];
        $rules = ['postal_codes.*' => 'postal_code_for:countries.*'];

        $this->assertPasses($request, $rules);

        # Partially empty country code
        $request = ['countries' => ['SJ', ''], 'postal_codes' => ['9170', '50100']];
        $rules = ['postal_codes.*' => 'postal_code_for:countries.*'];

        $this->assertPasses($request, $rules);
    }

    /**
     * Test validation of invalid input, using asterisks.
     *
     * @return void
     */
    public function testInvalidAsterisksValidation()
    {
        if ($this->strategy !== 'extendDependent') {
            $this->markTestSkipped('Dependent validation rules not supported');
        }

        # Partially valid postal codes
        $request = ['countries' => ['SO', 'TW'], 'postal_codes' => ['JH 09010', 'Invalid postal code']];
        $rules = ['postal_codes.*' => 'postal_code_for:countries.*'];

        $this->assertFails($request, $rules);

        # Invalid postal codes
        $request = ['countries' => ['ET', 'EH'], 'postal_codes' => ['Invalid postal code', 'Invalid postal code']];
        $rules = ['postal_codes.*' => 'postal_code_for:countries.*'];

        $this->assertFails($request, $rules);

        # Partially invalid country codes
        $request = ['countries' => ['MK', 'Invalid country code'], 'postal_codes' => ['1314', '02860']];
        $rules = ['postal_codes.*' => 'postal_code_for:countries.*'];

        $this->assertFails($request, $rules);
    }
}
