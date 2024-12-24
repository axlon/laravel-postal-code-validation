<?php

declare(strict_types=1);

namespace Tests\Integration;

use Axlon\PostalCodeValidation\Support\Facades\PostalCodes;
use Tests\TestCase;

class FacadeTest extends TestCase
{
    public function testFacadesProxiesPatternMatcher(): void
    {
        self::assertSame($this->app?->make('postal_codes'), PostalCodes::getFacadeRoot());
    }
}
