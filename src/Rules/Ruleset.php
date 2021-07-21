<?php

namespace Axlon\PostalCodeValidation\Rules;

use Axlon\PostalCodeValidation\Contracts\Ruleset as RulesetContract;

abstract class Ruleset implements RulesetContract
{
    /**
     * The validation overrides.
     *
     * @var array
     */
    protected $overrides = [];

    /**
     * Get the validation override for the given key.
     *
     * @param string $key
     * @return string
     */
    public function getOverride(string $key): string
    {
        return $this->overrides[$key];
    }

    /**
     * Determine if a validation override exists for the given key.
     *
     * @param string $key
     * @return bool
     */
    public function hasOverride(string $key): bool
    {
        return array_key_exists($key, $this->overrides);
    }

    /**
     * Override validation for the given key(s).
     *
     * @param array|string $key
     * @param string|null $rule
     */
    public function override($key, ?string $rule = null): void
    {
        if (is_array($key)) {
            $this->overrides = array_merge(
                $this->overrides,
                array_change_key_case($key, CASE_UPPER),
            );
        } else {
            $this->overrides[$key] = $rule;
        }
    }
}
