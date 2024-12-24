<?php

declare(strict_types=1);

namespace Tests\Integration;

use Tests\TestCase;

class ReplacerTest extends TestCase
{
    public function testPostalCodeReplacer(): void
    {
        $locale = is_callable([$this->app, 'getLocale']) ? $this->app->getLocale() : 'en';
        $translator = $this->app->make('translator');

        $validator = $this->app->make('validator')->make(
            ['postal_code' => 'not-a-postal-code'],
            ['postal_code' => 'postal_code:NL'],
        );

        $translator->addLines([
            'validation.postal_code' => ':attribute invalid, should be a :countries postal code (e.g. :examples)',
        ], $locale);

        self::assertContains(
            'postal code invalid, should be a NL postal code (e.g. 1234 AB)',
            $validator->errors()->all(),
        );
    }

    public function testPostalCodeForReplacer(): void
    {
        $locale = is_callable([$this->app, 'getLocale']) ? $this->app->getLocale() : 'en';
        $translator = $this->app->make('translator');

        $validator = $this->app->make('validator')->make(
            ['postal_code' => 'not-a-postal-code', 'country' => 'NL'],
            ['postal_code' => 'postal_code_for:country'],
        );

        $translator->addLines([
            'validation.postal_code_for' => ':attribute invalid, should be a :countries postal code (e.g. :examples)',
        ], $locale);

        self::assertContains(
            'postal code invalid, should be a NL postal code (e.g. 1234 AB)',
            $validator->errors()->all(),
        );
    }
}
