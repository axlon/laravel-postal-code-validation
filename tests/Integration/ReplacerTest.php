<?php

namespace Axlon\PostalCodeValidation\Tests\Integration;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class ReplacerTest extends TestCase
{
    /**
     * Test the error replacer for the 'postal_code' rule.
     *
     * @return void
     */
    public function testPostalCodeReplacer(): void
    {
        $validator = Validator::make(
            ['postal_code' => 'not-a-postal-code'],
            ['postal_code' => 'postal_code:NL']
        );

        Lang::addLines([
            'validation.postal_code' => ':attribute invalid, should be a :countries postal code (e.g. :examples)',
        ], Lang::getLocale());

        self::assertContains(
            'postal code invalid, should be a NL postal code (e.g. 1234 AB)',
            $validator->errors()->all()
        );
    }

    public function testPostalCodeWithReplacerIgnoresNonStringFields(): void
    {
        $validator = Validator::make(
            ['postal_code' => 'not-a-postal-code', 'array' => ['NL'], 'country' => 'NL'],
            ['postal_code' => 'postal_code_with:array,country']
        );

        Lang::addLines([
            'validation.postal_code_with' => ':attribute invalid, should be a :countries postal code (e.g. :examples)',
        ], Lang::getLocale());

        self::assertContains(
            'postal code invalid, should be a NL postal code (e.g. 1234 AB)',
            $validator->errors()->all()
        );
    }

    /**
     * Test the error replacer for the 'postal_code_for' rule.
     *
     * @return void
     */
    public function testPostalCodeForReplacer(): void
    {
        $validator = Validator::make(
            ['postal_code' => 'not-a-postal-code', 'country' => 'NL'],
            ['postal_code' => 'postal_code_for:country']
        );

        Lang::addLines([
            'validation.postal_code_for' => ':attribute invalid, should be a :countries postal code (e.g. :examples)',
        ], Lang::getLocale());

        self::assertContains(
            'postal code invalid, should be a NL postal code (e.g. 1234 AB)',
            $validator->errors()->all()
        );
    }
}
