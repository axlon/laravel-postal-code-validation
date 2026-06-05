<?php

declare(strict_types=1);

namespace Axlon\PostalCodeValidation\Tests\Integration;

use Axlon\PostalCodeValidation\Support\Facades\PostalCodes;
use Axlon\PostalCodeValidation\Tests\TestCase;
use Illuminate\Support\Facades\App;

final class FacadeTest extends TestCase
{
    /**
     * Test if the facade properly proxies the pattern matcher instance.
     *
     * @return void
     */
    public function testFacadesProxiesPatternMatcher(): void
    {
        self::assertSame(App::make('postal_codes'), PostalCodes::getFacadeRoot());
    }
}
