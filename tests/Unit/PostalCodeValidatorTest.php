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
        $constraint = $this->validator->get('IC');

        self::assertNotNull($constraint);
        self::assertTrue($constraint->matches('38580'));
    }

    #[DataProvider('provideExamples')]
    public function testExamplesAreValidPatterns(string $country, string $example): void
    {
        $constraint = $this->validator->get($country);

        self::assertNotNull($constraint);
        self::assertTrue($constraint->matches($example));
    }

    /**
     * @link https://github.com/axlon/laravel-postal-code-validation/issues/13
     */
    public function testGreatBritainInwardCodeMaxLength(): void
    {
        $constraint = $this->validator->get('GB');

        self::assertNotNull($constraint);
        self::assertFalse($constraint->matches('NN1 5LLL'));
    }

    public function testLowerCaseAreaCode(): void
    {
        $constraint = $this->validator->get('nl');

        self::assertNotNull($constraint);
        self::assertTrue($constraint->matches('1234 AB'));
    }

    public function testNullPattern(): void
    {
        $constraint = $this->validator->get('GH');

        self::assertNotNull($constraint);
        self::assertTrue($constraint->matches('any value'));
    }

    public function testUnsupportedAreaCode(): void
    {
        self::assertNull($this->validator->get('XX'));
    }
}
