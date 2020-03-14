<?php

namespace Axlon\PostalCodeValidation\Tests\Integration;

use Axlon\PostalCodeValidation\PostalCodeValidator;
use Axlon\PostalCodeValidation\Tests\Helpers\InteractsWithLaravel;
use Axlon\PostalCodeValidation\ValidationServiceProvider;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class ServiceProviderTest extends TestCase
{
    use InteractsWithLaravel,
        MockeryPHPUnitIntegration;

    /**
     * Test that the validation rules are registered after
     * the validator gets resolved.
     *
     * @return void
     */
    public function testRulesAreRegisteredAfterResolvingValidator(): void
    {
        [$app, $validator] = $this->getLaravelWithMockValidator();

        $validator->shouldReceive('extend')->once()
            ->with('postal_code', '\Axlon\PostalCodeValidation\ValidationExtension@validatePostalCode');

        $validator->shouldReceive('replacer')->once()
            ->with('postal_code', '\Axlon\PostalCodeValidation\Replacer@replacePostalCode');

        $validator->shouldReceive('replacer')->once()
            ->with('postal_code_for', '\Axlon\PostalCodeValidation\Replacer@replacePostalCodeFor');

        $validator->shouldReceive('extendDependent')->once()
            ->with('postal_code_for', '\Axlon\PostalCodeValidation\ValidationExtension@validatePostalCodeFor');

        $app->register(ValidationServiceProvider::class);
        $app->make('validator');
    }

    /**
     * Test that the validation rules are registered even if the validator
     * was resolved prior to loading the service provider.
     *
     * @return void
     */
    public function testRulesAreRegisteredEvenIfValidatorWasResolvedPrior(): void
    {
        [$app, $validator] = $this->getLaravelWithMockValidator();

        $app->make('validator');

        $validator->shouldReceive('extend')->once()
            ->with('postal_code', '\Axlon\PostalCodeValidation\ValidationExtension@validatePostalCode');

        $validator->shouldReceive('replacer')->once()
            ->with('postal_code', '\Axlon\PostalCodeValidation\Replacer@replacePostalCode');

        $validator->shouldReceive('replacer')->once()
            ->with('postal_code_for', '\Axlon\PostalCodeValidation\Replacer@replacePostalCodeFor');

        $validator->shouldReceive('extendDependent')->once()
            ->with('postal_code_for', '\Axlon\PostalCodeValidation\ValidationExtension@validatePostalCodeFor');

        $app->register(ValidationServiceProvider::class);
    }

    /**
     * Test that the validator is bound to the container.
     *
     * @return void
     */
    public function testValidatorRegistration(): void
    {
        $app = $this->getLaravel();
        $app->register(ValidationServiceProvider::class);

        $this->assertTrue($app->bound('validator.postal_codes'));
        $this->assertTrue($app->bound(PostalCodeValidator::class));

        $this->assertInstanceOf(PostalCodeValidator::class, $app->make('validator.postal_codes'));
        $this->assertInstanceOf(PostalCodeValidator::class, $app->make(PostalCodeValidator::class));
    }
}
