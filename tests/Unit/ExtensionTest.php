<?php

namespace Axlon\PostalCodeValidation\Tests\Unit;

use Axlon\PostalCodeValidation\PostalCodeValidator;
use Axlon\PostalCodeValidation\ValidationExtension;
use Illuminate\Validation\Validator;
use InvalidArgumentException;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class ExtensionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * The postal code validator.
     *
     * @var \Axlon\PostalCodeValidation\PostalCodeValidator|\Mockery\MockInterface
     */
    protected $postalCodeValidator;

    /**
     * The validator.
     *
     * @var \Illuminate\Validation\Validator|\Mockery\MockInterface
     */
    protected $validator;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->postalCodeValidator = Mockery::mock(PostalCodeValidator::class);
        $this->validator = Mockery::mock(Validator::class)->makePartial();
    }

    /**
     * Test if the postal_code_for rule fails without running validation
     * when it's passed an empty value.
     *
     * @return void
     */
    public function testPostalCodeForRuleFailsOnEmptyValue(): void
    {
        $extension = new ValidationExtension($this->postalCodeValidator);

        $this->validator->shouldReceive('getData')->andReturn(['field' => 'code']);
        $this->postalCodeValidator->shouldNotReceive('supports');
        $this->postalCodeValidator->shouldNotReceive('validate');

        $result = $extension->validatePostalCodeFor('attribute', '', ['field'], $this->validator);
        $this->assertFalse($result);

        $result = $extension->validatePostalCodeFor('attribute', null, ['field'], $this->validator);
        $this->assertFalse($result);
    }

    /**
     * Test if the postal_code_for rule fails when there are no matching patterns.
     *
     * @return void
     */
    public function testPostalCodeForRuleFailsWhenThereAreNoMatches(): void
    {
        $extension = new ValidationExtension($this->postalCodeValidator);

        $this->validator->shouldReceive('getData')->andReturn(['field' => 'code']);
        $this->postalCodeValidator->shouldReceive('supports')->once()->with('code')->andReturnTrue();
        $this->postalCodeValidator->shouldReceive('validate')->once()->with('code', 'value')->andReturnFalse();

        $result = $extension->validatePostalCodeFor('attribute', 'value', ['field'], $this->validator);
        $this->assertFalse($result);
    }

    /**
     * Test if the postal_code_for rule ignores unsupported country codes.
     *
     * @return void
     */
    public function testPostalCodeForRuleIgnoresUnsupportedParameters(): void
    {
        $extension = new ValidationExtension($this->postalCodeValidator);

        $this->validator->shouldReceive('getData')->andReturn(['field' => 'code', 'field2' => 'code2']);
        $this->postalCodeValidator->shouldReceive('supports')->with('code')->andReturnFalse();
        $this->postalCodeValidator->shouldNotReceive('validate')->with('code', 'value');
        $this->postalCodeValidator->shouldReceive('supports')->once()->with('code2')->andReturnTrue();
        $this->postalCodeValidator->shouldReceive('validate')->once()->with('code2', 'value')->andReturnTrue();

        $result = $extension->validatePostalCodeFor('attribute', 'value', ['field', 'field2'], $this->validator);
        $this->assertTrue($result);
    }

    /**
     * Test if the postal_code_for rule passes on correct input.
     *
     * @return void
     */
    public function testPostalCodeForRulePasses(): void
    {
        $extension = new ValidationExtension($this->postalCodeValidator);

        $this->validator->shouldReceive('getData')->once()->andReturn(['field' => 'code']);
        $this->postalCodeValidator->shouldReceive('supports')->once()->with('code')->andReturnTrue();
        $this->postalCodeValidator->shouldReceive('validate')->once()->with('code', 'value')->andReturnTrue();

        $result = $extension->validatePostalCodeFor('attribute', 'value', ['field'], $this->validator);
        $this->assertTrue($result);
    }

    /**
     * Test if the postal_code_for rule passes on correct input
     * when a parameter references a non-root value.
     *
     * @return void
     */
    public function testPostalCodeForRulePassesWithDeepReference(): void
    {
        $extension = new ValidationExtension($this->postalCodeValidator);

        $this->validator->shouldReceive('getData')->once()->andReturn(['fields' => ['ignored_code', 'code']]);
        $this->postalCodeValidator->shouldNotReceive('supports')->with('ignored_code');
        $this->postalCodeValidator->shouldNotReceive('validate')->with('ignored_code', 'value');
        $this->postalCodeValidator->shouldReceive('supports')->once()->with('code')->andReturnTrue();
        $this->postalCodeValidator->shouldReceive('validate')->once()->with('code', 'value')->andReturnTrue();

        $result = $extension->validatePostalCodeFor('attribute.1', 'value', ['fields.1'], $this->validator);
        $this->assertTrue($result);
    }

    /**
     * Test if the postal_code_for rule passes without running validation
     * when none of the referenced fields are filled.
     *
     * @return void
     */
    public function testPostalCodeForRulePassesWhenNoReferencedFieldsAreFilled(): void
    {
        $extension = new ValidationExtension($this->postalCodeValidator);

        $this->postalCodeValidator->shouldNotReceive('supports');
        $this->postalCodeValidator->shouldNotReceive('validate');

        $this->validator->shouldReceive('getData')->once()->andReturn(['field' => null]);
        $result = $extension->validatePostalCodeFor('attribute', 'value', ['field'], $this->validator);
        $this->assertTrue($result);

        $this->validator->shouldReceive('getData')->once()->andReturn(['field' => '']);
        $result = $extension->validatePostalCodeFor('attribute', 'value', ['field'], $this->validator);
        $this->assertTrue($result);
    }

    /**
     * Test if the postal_code_for rule passes without running validation
     * when none of the referenced fields are present.
     *
     * @return void
     */
    public function testPostalCodeForRulePassesWhenNoReferencedFieldsArePresent(): void
    {
        $extension = new ValidationExtension($this->postalCodeValidator);

        $this->postalCodeValidator->shouldNotReceive('supports');
        $this->postalCodeValidator->shouldNotReceive('validate');

        $this->validator->shouldReceive('getData')->once()->andReturn([]);
        $result = $extension->validatePostalCodeFor('attribute', 'value', ['field'], $this->validator);
        $this->assertTrue($result);
    }

    /**
     * Test if the postal_code_for rule throws an exception when it
     * receives no arguments.
     *
     * @return void
     */
    public function testPostalCodeForRuleThrowsWithoutArguments(): void
    {
        $extension = new ValidationExtension($this->postalCodeValidator);

        $this->expectException(InvalidArgumentException::class);
        $extension->validatePostalCodeFor('attribute', 'value', [], $this->validator);
    }

    /**
     * Test if the postal_code rule fails without running validation
     * when an empty value is passed.
     *
     * @return void
     */
    public function testPostalCodeRuleFailsOnEmptyValue(): void
    {
        $extension = new ValidationExtension($this->postalCodeValidator);

        $this->postalCodeValidator->shouldNotReceive('supports');
        $this->postalCodeValidator->shouldNotReceive('validate');

        $result = $extension->validatePostalCode('attribute', '', ['code'], $this->validator);
        $this->assertFalse($result);

        $result = $extension->validatePostalCode('attribute', null, ['code'], $this->validator);
        $this->assertFalse($result);
    }

    /**
     * Test if the postal_code rule fails when there are no matches.
     *
     * @return void
     */
    public function testPostalCodeRuleFailsWhenThereAreNoMatches(): void
    {
        $extension = new ValidationExtension($this->postalCodeValidator);

        $this->postalCodeValidator->shouldReceive('supports')->once()->with('code')->andReturnTrue();
        $this->postalCodeValidator->shouldReceive('validate')->once()->with('code', 'value')->andReturnFalse();

        $result = $extension->validatePostalCode('attribute', 'value', ['code'], $this->validator);
        $this->assertFalse($result);
    }

    /**
     * Test if the postal_code rule fails when it's passed an
     * unsupported country code.
     *
     * @return void
     */
    public function testPostalCodeRuleFailsOnUnsupportedParameter(): void
    {
        $extension = new ValidationExtension($this->postalCodeValidator);

        $this->postalCodeValidator->shouldReceive('supports')->once()->with('code')->andReturnFalse();
        $this->postalCodeValidator->shouldNotReceive('validate');

        $result = $extension->validatePostalCode('attribute', 'value', ['code'], $this->validator);
        $this->assertFalse($result);
    }

    /**
     * Test if the postal_code rule passes on correct input.
     *
     * @return void
     */
    public function testPostalCodeRulePasses(): void
    {
        $extension = new ValidationExtension($this->postalCodeValidator);

        $this->postalCodeValidator->shouldReceive('supports')->once()->with('code')->andReturnTrue();
        $this->postalCodeValidator->shouldReceive('validate')->once()->with('code', 'value')->andReturnTrue();

        $result = $extension->validatePostalCode('attribute', 'value', ['code'], $this->validator);
        $this->assertTrue($result);
    }

    /**
     * Test if the postal_code rule throws an exception when it
     * receives no arguments.
     */
    public function testPostalCodeRuleThrowsWithoutArguments(): void
    {
        $extension = new ValidationExtension($this->postalCodeValidator);

        $this->expectException(InvalidArgumentException::class);
        $extension->validatePostalCode('attribute', 'value', [], $this->validator);
    }
}
