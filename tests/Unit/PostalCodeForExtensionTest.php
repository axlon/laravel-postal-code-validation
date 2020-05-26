<?php

namespace Axlon\PostalCodeValidation\Tests\Unit;

use Axlon\PostalCodeValidation\Extensions\PostalCodeFor;
use Axlon\PostalCodeValidation\PatternMatcher;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Mockery;
use PHPUnit\Framework\TestCase;

class PostalCodeForExtensionTest extends TestCase
{
    /**
     * The pattern matcher.
     *
     * @var \Axlon\PostalCodeValidation\PatternMatcher|\Mockery\MockInterface
     */
    protected $matcher;

    /**
     * The HTTP request.
     *
     * @var \Illuminate\Http\Request|\Mockery\MockInterface
     */
    protected $request;

    /**
     * The validator.
     *
     * @var \Illuminate\Contracts\Validation\Validator|\Mockery\MockInterface
     */
    protected $validator;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->matcher = Mockery::mock(PatternMatcher::class);
        $this->request = Mockery::mock(Request::class);
        $this->validator = Mockery::mock(Validator::class);
    }

    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
        Mockery::close();
    }

    /**
     * Test if validation of empty input always fails.
     *
     * @return void
     */
    public function testValidationOfEmptyInputFails(): void
    {
        $extension = new PostalCodeFor($this->matcher, $this->request);

        $this->assertFalse($extension->validate('attribute', null, ['country'], $this->validator));
        $this->assertFalse($extension->validate('attribute', '', ['country'], $this->validator));
    }

    /**
     * Test if validation of a non-matching postal code fails.
     *
     * @return void
     */
    public function testValidationOfInvalidPostalCodeFails(): void
    {
        $extension = new PostalCodeFor($this->matcher, $this->request);

        $this->request->expects('all')->once()->andReturn(['country' => 'failing country']);
        $this->matcher->expects('supports')->with('failing country')->once()->andReturnTrue();
        $this->matcher->expects('passes')->with('failing country', 'postal code')->once()->andReturnFalse();

        $this->assertFalse($extension->validate('attribute', 'postal code', ['country'], $this->validator));
    }

    /**
     * Test if validation using an unsupported country code fails.
     *
     * @return void
     */
    public function testValidationOfUnsupportedCountryFails(): void
    {
        $extension = new PostalCodeFor($this->matcher, $this->request);

        $this->request->expects('all')->once()->andReturn(['country' => 'unsupported country']);
        $this->matcher->expects('supports')->with('unsupported country')->once()->andReturnFalse();

        $this->assertFalse($extension->validate('attribute', 'postal code', ['country'], $this->validator));
    }

    /**
     * Test if validation keeps trying countries if it encounters a failure (for whatever reason).
     *
     * @return void
     */
    public function testValidationSkipsFailures(): void
    {
        $extension = new PostalCodeFor($this->matcher, $this->request);

        $parameters = [
            'country 1', 'country 2',
            'country 3', 'country 4',
        ];

        $requestData = [
            'country 1' => 'failing country',
            'country 3' => 'unsupported country',
            'country 4' => 'passing country',
        ];

        $this->request->expects('all')->withNoArgs()->times(4)->andReturn($requestData);

        $this->matcher->expects('supports')->with('failing country')->once()->andReturnTrue();
        $this->matcher->expects('passes')->with('failing country', 'postal code')->once()->andReturnFalse();

        $this->matcher->expects('supports')->with('unsupported country')->once()->andReturnFalse();

        $this->matcher->expects('supports')->with('passing country')->once()->andReturnTrue();
        $this->matcher->expects('passes')->with('passing country', 'postal code')->once()->andReturnTrue();

        $this->assertTrue($extension->validate('attribute', 'postal code', $parameters, $this->validator));
    }

    /**
     * Test if validation of a matching postal code passes.
     *
     * @return void
     */
    public function testValidationOfValidPostalCodePasses(): void
    {
        $extension = new PostalCodeFor($this->matcher, $this->request);

        $this->request->expects('all')->once()->andReturn(['country' => 'passing country']);
        $this->matcher->expects('supports')->with('passing country')->once()->andReturnTrue();
        $this->matcher->expects('passes')->with('passing country', 'postal code')->once()->andReturnTrue();

        $this->assertTrue($extension->validate('attribute', 'postal code', ['country'], $this->validator));
    }

    /**
     * Test if validation without parameters throws an exception.
     *
     * @return void
     */
    public function testValidationWithoutParametersThrows(): void
    {
        $extension = new PostalCodeFor($this->matcher, $this->request);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Validation rule postal_code_for requires at least 1 parameter.');

        $extension->validate('attribute', 'postal_code', [], $this->validator);
    }
}
