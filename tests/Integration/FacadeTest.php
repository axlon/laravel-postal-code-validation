<?php

declare(strict_types=1);

namespace Tests\Integration;

use Axlon\PostalCodeValidation\Support\Facades\PostalCodes;
use Tests\TestCase;

class FacadeTest extends TestCase
{
    /**
     * Test if the facade properly proxies the pattern matcher instance.
     *
     * @return void
     */
    public function testFacadesProxiesPatternMatcher(): void
    {
        self::assertSame($this->app->make('postal_codes'), PostalCodes::getFacadeRoot());
    }
}
