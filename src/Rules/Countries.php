<?php

namespace Axlon\PostalCodeValidation\Rules;

use Closure;

class Countries extends Ruleset
{
    /**
     * The validation rules.
     *
     * @var array
     */
    protected $rules;

    /**
     * Create a new ISO 3166-1 alpha 2 validation ruleset.
     *
     * @param \Closure $resolver
     * @return void
     */
    public function __construct(Closure $resolver)
    {
        $this->rules = $resolver();
    }

    /**
     * @inheritDoc
     */
    public function getExample(string $key): string
    {
        return $this->rules[$key][1];
    }

    /**
     * Get the explicit validation rule for the given key.
     *
     * @param string $key
     * @return string
     */
    public function getExplicitRule(string $key): string
    {
        return '/^' . $this->rules[$key][0] . '$/i';
    }

    /**
     * Get the fallback validation rule.
     *
     * @return string
     */
    public function getFallbackRule(): string
    {
        return '/.*/';
    }

    /**
     * @inheritDoc
     */
    public function getRule(string $key): string
    {
        if ($this->hasOverride($key)) {
            return $this->getOverride($key);
        }

        return $this->hasExplicitRule($key)
            ? $this->getExplicitRule($key)
            : $this->getFallbackRule();
    }

    /**
     * @inheritDoc
     */
    public function hasExample(string $key): bool
    {
        return isset($this->rules[$key][1]);
    }

    /**
     * Determine whether a explicit validation rule exists for the given key.
     *
     * @param string $key
     * @return bool
     */
    public function hasExplicitRule(string $key): bool
    {
        return isset($this->rules[$key][0]);
    }

    /**
     * @inheritDoc
     */
    public function hasRule(string $key): bool
    {
        return array_key_exists($key, $this->rules);
    }
}
