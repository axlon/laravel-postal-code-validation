<?php

declare(strict_types=1);

namespace Axlon\PostalCodeValidation\Constraints;

use Axlon\PostalCodeValidation\Contracts\Constraint;

/**
 * @internal
 */
final class RegularExpression implements Constraint
{
    /**
     * Create a new regular expression.
     *
     * @param string $pattern
     */
    public function __construct(
        protected string $pattern,
    ) {
    }

    /**
     * Determine if the given value satisfies the constraint.
     *
     * @param string $value
     * @return bool
     */
    public function matches(string $value): bool
    {
        return preg_match($this->pattern, $value) === 1;
    }
}
