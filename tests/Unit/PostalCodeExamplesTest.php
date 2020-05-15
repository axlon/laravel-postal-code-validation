<?php

namespace Axlon\PostalCodeValidation\Tests\Unit;

use Axlon\PostalCodeValidation\PostalCodeExamples;
use PHPUnit\Framework\TestCase;

class PostalCodeExamplesTest extends TestCase
{
    /**
     * Test the retrieval of valid postal codes.
     *
     * @return void
     */
    public function testExampleRetrieval(): void
    {
        $examples = new class {
            use PostalCodeExamples;
        };

        $this->assertEquals('1234 AB', $examples->exampleFor('NL'));
        $this->assertEquals('4000', $examples->exampleFor('be'));
        $this->assertNull($examples->exampleFor('XX'));
    }
}
