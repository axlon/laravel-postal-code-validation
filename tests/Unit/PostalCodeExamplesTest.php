<?php

namespace Axlon\PostalCodeValidation\Tests\Unit;

use Axlon\PostalCodeValidation\Support\PostalCodeExamples;
use PHPUnit\Framework\TestCase;

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
        $this->assertEquals('1234 AB', $this->examples->get('NL'));
        $this->assertEquals('4000', $this->examples->get('be')); # Lowercase country code
        $this->assertNull($this->examples->get('GH')); # Country code without a pattern
        $this->assertNull($this->examples->get('XX')); # Non-existent country code
    }
}
