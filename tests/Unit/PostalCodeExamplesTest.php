<?php

namespace Tests\Unit;

use Axlon\PostalCodeValidation\Support\PostalCodeExamples;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(PostalCodeExamples::class)]
class PostalCodeExamplesTest extends TestCase
{
    /**
     * The postal code examples.
     *
     * @var \Axlon\PostalCodeValidation\Support\PostalCodeExamples
     */
    protected $examples;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->examples = new PostalCodeExamples();
    }

    /**
     * Test the retrieval of valid postal code examples.
     *
     * @return void
     */
    public function testExampleRetrieval(): void
    {
        self::assertSame('1234 AB', $this->examples->get('NL'));
        self::assertSame('4000', $this->examples->get('be')); # Lowercase country code
        self::assertNull($this->examples->get('GH')); # Country code without a pattern
        self::assertNull($this->examples->get('XX')); # Non-existent country code
    }
}
