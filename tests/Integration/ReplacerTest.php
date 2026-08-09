<?php

declare(strict_types=1);

namespace Tests\Integration;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

final class ReplacerTest extends TestCase
{
    public function testPostalCodeReplacer(): void
    {
        Lang::addLines([
            'validation.postal_code' => ':attribute invalid, should be a :countries postal code (e.g. :examples)',
        ], 'en');

        $validator = Validator::make(
            ['postal_code' => 'not-a-postal-code'],
            ['postal_code' => 'postal_code:NL'],
        );

        self::assertContains(
            'postal code invalid, should be a NL postal code (e.g. 1234 AB)',
            $validator->errors()->all(),
        );
    }

    public function testPostalCodeWithReplacer(): void
    {
        Lang::addLines([
            'validation.postal_code_with' => ':attribute invalid, should be a :countries postal code (e.g. :examples)',
        ], 'en');

        $validator = Validator::make(
            ['postal_code' => 'not-a-postal-code', 'country' => 'NL'],
            ['postal_code' => 'postal_code_with:country'],
        );

        self::assertContains(
            'postal code invalid, should be a NL postal code (e.g. 1234 AB)',
            $validator->errors()->all(),
        );
    }

    public function testPostalCodeWithReplacerIgnoresNonStringFields(): void
    {
        Lang::addLines([
            'validation.postal_code_with' => ':attribute invalid, should be a :countries postal code (e.g. :examples)',
        ], 'en');

        $validator = Validator::make(
            ['postal_code' => 'not-a-postal-code', 'array' => ['NL'], 'country' => 'NL'],
            ['postal_code' => 'postal_code_with:array,country'],
        );

        self::assertContains(
            'postal code invalid, should be a NL postal code (e.g. 1234 AB)',
            $validator->errors()->all(),
        );
    }
}
