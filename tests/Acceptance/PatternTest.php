<?php

namespace Axlon\PostalCodeValidation\Tests\Acceptance;

use PHPUnit\Framework\TestCase;

class PatternTest extends TestCase
{
    /**
     * Provide the patterns.
     *
     * @return array
     */
    public function providePatterns(): array
    {
        return require __DIR__ . '/../../resources/formats.php';
    }

    /**
     * Test if every example matches it's pattern.
     *
     * @param string|null $example
     * @param string|null $pattern
     * @return void
     * @dataProvider providePatterns
     */
    public function testExampleMatchesPattern(?string $example, ?string $pattern): void
    {
        if ($pattern === null) {
            $this->assertNull($example);
        } else {
            $this->assertRegExp($pattern, $example);
        }
    }
}
