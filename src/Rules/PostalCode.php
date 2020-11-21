<?php

namespace Axlon\PostalCodeValidation\Rules;

class PostalCode
{
    /**
     * Whether or not this rule is dependant.
     *
     * @var bool
     */
    protected $dependent;

    /**
     * The rule parameters.
     *
     * @var string[]
     */
    protected $parameters;

    /**
     * Create a new postal code validation rule.
     *
     * @param array $parameters
     * @param bool $dependant
     * @return void
     */
    public function __construct(array $parameters, bool $dependant)
    {
        $this->dependent = $dependant;
        $this->parameters = $parameters;
    }

    /**
     * Convert the rule to a validation string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return 'postal_code' . ($this->dependent ? '_with:' : ':') . implode(',', $this->parameters);
    }

    /**
     * Get a postal_code_with constraint builder instance.
     *
     * @param string $country
     * @return static
     */
    public static function for(string $country): self
    {
        return static::forCountry($country);
    }

    /**
     * Create a new postal code validation rule for given countries.
     *
     * @param string ...$parameters
     * @return static
     */
    public static function forCountry(string ...$parameters): self
    {
        return new static($parameters, false);
    }

    /**
     * Create a new postal code validation rule for given inputs.
     *
     * @param string ...$parameters
     * @return static
     */
    public static function forInput(string ...$parameters): self
    {
        return new static($parameters, true);
    }

    /**
     * Add additional validation parameters to the rule.
     *
     * @param string ...$parameters
     * @return $this
     */
    public function or(string ...$parameters): self
    {
        $this->parameters = array_merge($this->parameters, $parameters);

        return $this;
    }

    /**
     * Get a postal_code_with constraint builder instance.
     *
     * @param string $field
     * @return static
     */
    public static function with(string $field): self
    {
        return static::forInput($field);
    }
}
