<?php

declare(strict_types=1);

namespace Axlon\PostalCodeValidation\Build\Http;

final readonly class AddressValidationData
{
    /**
     * Create new address validation data.
     *
     * @param string $key
     * @param array{pattern: string, examples: non-empty-list<string>}|null $postalCode
     * @return void
     */
    public function __construct(
        public string $key,
        public ?array $postalCode,
    ) {
    }
}
