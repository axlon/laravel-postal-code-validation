<?php

namespace Axlon\PostalCodeValidation\Tests\Rules;

use Axlon\PostalCodeValidation\Rules\ISO3166_1\Alpha2;
use Axlon\PostalCodeValidation\Tests\TestCase;

class Alpha2Test extends TestCase
{
    public function testItHandlesExplicitRules(): void
    {
        $rules = new Alpha2(function () {
            return [
                'test' => ['pattern'],
            ];
        });

        $this->assertTrue($rules->hasExplicitRule('test'));
        $this->assertFalse($rules->hasExample('test'));
        $this->assertTrue($rules->hasRule('test'));
        $this->assertSame('/^pattern$/i', $rules->getRule('test'));
    }

    public function testItHandlesEmptyRules(): void
    {
        $rules = new Alpha2(function () {
            return [
                'test' => [],
            ];
        });

        $this->assertFalse($rules->hasExplicitRule('test'));
        $this->assertFalse($rules->hasExample('test'));
        $this->assertTrue($rules->hasRule('test'));
        $this->assertSame('/.*/', $rules->getRule('test'));
    }

    public function testItHandlesExamples(): void
    {
        $rules = new Alpha2(function () {
            return [
                'test' => ['pattern', 'example'],
            ];
        });

        $this->assertTrue($rules->hasExample('test'));
        $this->assertSame('example', $rules->getExample('test'));
    }

    public function testItHandlesOverrides(): void
    {
        $rules = new Alpha2(function () {
            return [
                'test' => ['test'],
            ];
        });

        $rules->override('test', 'override');

        $this->assertTrue($rules->hasExplicitRule('test'));
        $this->assertTrue($rules->hasOverride('test'));
        $this->assertSame('override', $rules->getRule('test'));
    }
}
