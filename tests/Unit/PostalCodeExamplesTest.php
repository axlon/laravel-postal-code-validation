<?php

namespace Axlon\PostalCodeValidation\Tests\Unit;

use Axlon\PostalCodeValidation\Support\PostalCodeExamples;
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
        $examples = new PostalCodeExamples();

        $this->assertEquals('1234 AB', $examples->get('NL'));
        $this->assertEquals('4000', $examples->get('be'));
        $this->assertNull($examples->get('XX'));
    }
}
