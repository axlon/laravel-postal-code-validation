<?php

declare(strict_types=1);

namespace Tests\Unit;

use Axlon\PostalCodeValidation\Support\PostalCodeExamples;
use PHPUnit\Framework\TestCase;

class PostalCodeExamplesTest extends TestCase
{
    /**
     * Test the retrieval of valid postal code examples.
     *
     * @return void
     */
    public function testExampleRetrieval(): void
    {
        $examples = new PostalCodeExamples();

        self::assertEquals('1234 AB', $examples->get('NL'));
        self::assertEquals('4000', $examples->get('be')); // Lowercase country code
        self::assertNull($examples->get('GH')); // Country code without a pattern
        self::assertNull($examples->get('XX')); // Non-existent country code
    }
}
