<?php

declare(strict_types=1);

namespace Tests\Unit;

use Axlon\PostalCodeValidation\Support\PostalCodeExamples;
use PHPUnit\Framework\TestCase;

final class PostalCodeExamplesTest extends TestCase
{
    protected PostalCodeExamples $examples;

    protected function setUp(): void
    {
        $this->examples = new PostalCodeExamples();
    }

    public function testExampleRetrieval(): void
    {
        self::assertSame('1234 AB', $this->examples->get('NL'));
        self::assertSame('4000', $this->examples->get('be')); // Lowercase country code
        self::assertNull($this->examples->get('GH')); // Country code without a pattern
        self::assertNull($this->examples->get('XX')); // Non-existent country code
    }
}
