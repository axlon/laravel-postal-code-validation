<?php

declare(strict_types=1);

namespace Tests\Unit;

use Axlon\PostalCodeValidation\PostalCodeValidator;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class PostalCodeValidatorTest extends TestCase
{
    protected PostalCodeValidator $validator;

    /**
     * @return \Illuminate\Support\Collection<string, array{string, string}>
     */
    public static function provideExamples(): Collection
    {
        /** @var array<string, string> $data */
        $data = require __DIR__ . '/../../resources/examples.php';

        return collect($data)->map(static function (string $example, string $country) {
            return [$country, $example];
        });
    }

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
        self::assertTrue($this->validator->passes('IC', '38580'));
    }

    #[DataProvider('provideExamples')]
    public function testExamplesAreValidPatterns(string $country, string $example): void
    {
        self::assertTrue($this->validator->passes($country, $example));
    }

    /**
     * @link https://github.com/axlon/laravel-postal-code-validation/issues/13
     */
    public function testGreatBritainInwardCodeMaxLength(): void
    {
        self::assertFalse($this->validator->passes('GB', 'NN1 5LLL'));
    }

    public function testLowerCaseCountryCode(): void
    {
        self::assertTrue($this->validator->supports('nl'));
        self::assertTrue($this->validator->passes('nl', '1234 AB'));
    }

    public function testNullPattern(): void
    {
        self::assertTrue($this->validator->supports('GH'));
        self::assertTrue($this->validator->passes('GH', 'any value'));
    }

    public function testUnsupportedCountryCode(): void
    {
        self::assertFalse($this->validator->supports('XX'));
        self::assertFalse($this->validator->passes('XX', 'any value'));
    }
}
