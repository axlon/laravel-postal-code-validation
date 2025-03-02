<?php

declare(strict_types=1);

namespace Axlon\PostalCodeValidation\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static bool fails(string $countryCode, string ...$postalCodes)
 * @method static bool passes(string $countryCode, string ...$postalCodes)
 * @method static void override(array|string $countryCode, string|null $pattern = null)
 * @method static bool supports(string $countryCode)
 * @see \Axlon\PostalCodeValidation\PostalCodeValidator
 */
final class PostalCodes extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'postal_codes';
    }
}
