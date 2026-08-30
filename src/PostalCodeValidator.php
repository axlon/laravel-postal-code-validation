<?php

declare(strict_types=1);

namespace Axlon\PostalCodeValidation;

use Axlon\PostalCodeValidation\Constraints\RegularExpression;
use Axlon\PostalCodeValidation\Constraints\Unconstrained;
use Axlon\PostalCodeValidation\Contracts\Constraint;
use Axlon\PostalCodeValidation\Contracts\ConstraintRepository;

final class PostalCodeValidator implements ConstraintRepository
{
    /**
     * The area codes that are aliases for other area codes.
     */
    private const ALIASES = [
        'IC' => 'ES',
    ];

    /**
     * Create a new postal code constraint repository.
     *
     * @param array<string, string|null> $patterns
     * @return void
     */
    public function __construct(
        protected array $patterns,
    ) {
    }

    /**
     * Get the postal code constraint for the specified area.
     *
     * @param string $areaCode
     * @return \Axlon\PostalCodeValidation\Contracts\Constraint|null
     */
    public function get(string $areaCode): ?Constraint
    {
        $areaCode = $this->resolveAlias($areaCode);

        if (!array_key_exists($areaCode, $this->patterns)) {
            return null;
        }

        if (($pattern = $this->patterns[$areaCode]) === null) {
            return new Unconstrained();
        }

        return new RegularExpression($pattern);
    }

    /**
     * Resolve the given area code to the area code its data is stored under.
     *
     * @param string $areaCode
     * @return string
     */
    private function resolveAlias(string $areaCode): string
    {
        $areaCode = strtoupper($areaCode);

        return self::ALIASES[$areaCode] ?? $areaCode;
    }
}
