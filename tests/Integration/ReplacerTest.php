<?php

namespace Axlon\PostalCodeValidation\Tests\Integration;

class ReplacerTest extends TestCase
{
    /**
     * Test the error replacer for the 'postal_code' rule.
     *
     * @return void
     */
    public function testPostalCodeReplacer(): void
    {
        $locale = $this->app->getLocale();
        $translator = $this->app->make('translator');

        $validator = $this->app->make('validator')->make(
            ['postal_code' => 'not-a-postal-code'],
            ['postal_code' => 'postal_code:NL']
        );

        $translator->addLines([
            'validation.postal_code' => ':attribute invalid, should be a :countries postal code (e.g. :examples)',
        ], $locale);

        $this->assertContains(
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
        $locale = $this->app->getLocale();
        $translator = $this->app->make('translator');

        $validator = $this->app->make('validator')->make(
            ['postal_code' => 'not-a-postal-code', 'country' => 'NL'],
            ['postal_code' => 'postal_code_for:country']
        );

        $translator->addLines([
            'validation.postal_code_for' => ':attribute invalid, should be a :countries postal code (e.g. :examples)',
        ], $locale);

        $this->assertContains(
            'postal code invalid, should be a NL postal code (e.g. 1234 AB)',
            $validator->errors()->all()
        );
    }
}
