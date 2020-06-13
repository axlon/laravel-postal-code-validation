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
        return 'postal_code' . ($this->dependent ? '_for:' : ':') . implode(',', $this->parameters);
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
}
