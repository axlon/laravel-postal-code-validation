<?php

declare(strict_types=1);

namespace Tests\Unit;

use Axlon\PostalCodeValidation\PostalCodeValidator;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;
use Tests\Generated\CountryDataProvider;

final class PostalCodeValidatorTest extends TestCase
{
    protected PostalCodeValidator $validator;

    protected function setUp(): void
    {
        /** @var array<string, string|null> $data */
        $data = require __DIR__ . '/../../resources/patterns.php';

        $this->validator = new PostalCodeValidator($data);
    }

    /**
     * @link https://github.com/axlon/laravel-postal-code-validation/issues/35
     */
    public function testCanaryIslands(): void
    {
        $constraint = $this->validator->get('IC');

        self::assertNotNull($constraint);
        self::assertTrue($constraint->test('38580'));
    }

    #[DataProviderExternal(CountryDataProvider::class, 'examples')]
    public function testExamplesAreValidPatterns(string $country, string $example): void
    {
        $constraint = $this->validator->get($country);

        self::assertNotNull($constraint);
        self::assertTrue($constraint->test($example));
    }

    /**
     * @link https://github.com/axlon/laravel-postal-code-validation/issues/13
     */
    public function testGreatBritainInwardCodeMaxLength(): void
    {
        $constraint = $this->validator->get('GB');

        self::assertNotNull($constraint);
        self::assertFalse($constraint->test('NN1 5LLL'));
    }

    public function testLowerCaseRegionCode(): void
    {
        self::assertNull($this->validator->get('nl'));
    }

    public function testNullPattern(): void
    {
        $constraint = $this->validator->get('GH');

        self::assertNotNull($constraint);
        self::assertTrue($constraint->test('any value'));
    }

    public function testUnsupportedRegionCode(): void
    {
        self::assertNull($this->validator->get('XX'));
    }
}
