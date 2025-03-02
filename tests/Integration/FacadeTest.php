<?php

declare(strict_types=1);

namespace Axlon\PostalCodeValidation\Tests\Integration;

use Axlon\PostalCodeValidation\Support\Facades\PostalCodes;

final class FacadeTest extends TestCase
{
    /**
     * Test if the facade properly proxies the pattern matcher instance.
     *
     * @return void
     */
    public function testFacadesProxiesPatternMatcher(): void
    {
        $this->assertSame($this->app->make('postal_codes'), PostalCodes::getFacadeRoot());
    }
}
