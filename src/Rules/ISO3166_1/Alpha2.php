<?php

namespace Axlon\PostalCodeValidation\Rules\ISO3166_1;

use Axlon\PostalCodeValidation\Rules\Ruleset;

class Alpha2 extends Ruleset
{
    /**
     * The validation examples.
     *
     * @var array
     */
    protected $examples;

    /**
     * The validation rules.
     *
     * @var array
     */
    protected $rules;

    /**
     * Create a new ISO 3166-1 alpha 2 validation ruleset.
     *
     * @return void
     */
    public function __construct()
    {
        $this->examples = require __DIR__ . '/../../../resources/examples.php';
        $this->rules = require __DIR__ . '/../../../resources/patterns.php';
    }

    /**
     * @inheritDoc
     */
    public function getExample(string $key): string
    {
        return $this->examples[$key];
    }

    /**
     * Get the explicit validation rule for the given key.
     *
     * @param string $key
     * @return string
     */
    public function getExplicitRule(string $key): string
    {
        return $this->rules[$key];
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
        return array_key_exists($key, $this->examples);
    }

    /**
     * Determine whether a explicit validation rule exists for the given key.
     *
     * @param string $key
     * @return bool
     */
    public function hasExplicitRule(string $key): bool
    {
        return !empty($this->rules[$key]);
    }

    /**
     * @inheritDoc
     */
    public function hasRule(string $key): bool
    {
        return array_key_exists($key, $this->rules);
    }
}
