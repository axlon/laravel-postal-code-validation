<?php

declare(strict_types=1);

namespace Axlon\PostalCodeValidation;

use Axlon\PostalCodeValidation\Constraints\Constraint;
use Axlon\PostalCodeValidation\Constraints\ConstraintRegistry;

final class PostalCodeValidator implements ConstraintRegistry
{
    /**
     * Create a new constraint registry.
     *
     * @param array<string, string|null> $patterns
     */
    public function __construct(
        protected array $patterns,
    ) {
    }

    /**
     * Retrieve a constraint for the specified region.
     *
     * @param string $regionCode
     * @return \Axlon\PostalCodeValidation\Constraints\Constraint|null
     */
    public function get(string $regionCode): ?Constraint
    {
        $regionCode = $regionCode === 'IC' ? 'ES' : $regionCode;

        if (array_key_exists($regionCode, $this->patterns)) {
            return self::makeConstraint($this->patterns[$regionCode] ?? '/.*/');
        }

        return null;
    }

    /**
     * Make a constraint from the given pattern.
     *
     * @param string $pattern
     * @return \Axlon\PostalCodeValidation\Constraints\Constraint
     */
    private static function makeConstraint(string $pattern): Constraint
    {
        return new class ($pattern) implements Constraint {
            public function __construct(
                private readonly string $pattern,
            ) {
            }

            public function test(string $value): bool
            {
                return preg_match($this->pattern, $value) === 1;
            }
        };
    }
}
