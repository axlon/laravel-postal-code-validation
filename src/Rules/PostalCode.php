<?php

namespace Axlon\PostalCodeValidation\Rules;

class PostalCode
{
    /**
     * Whether or not this rule depends on other request parameters.
     *
     * @var bool
     */
    protected $dependant;

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
    protected function __construct(array $parameters, bool $dependant)
    {
        $this->dependant = $dependant;
        $this->parameters = $parameters;
    }

    /**
     * Add additional parameters.
     *
     * @param string ...$parameters
     * @return $this
     */
    public function and(string ...$parameters)
    {
        $this->parameters = array_merge($this->parameters, $parameters);

        return $this;
    }

    /**
     * Create a new postal code rule for given countries.
     *
     * @param string ...$parameters
     * @return \Axlon\PostalCodeValidation\Rules\PostalCode
     */
    public static function for(string ...$parameters)
    {
        return new static($parameters, false);
    }

    /**
     * Create a new postal code rule dependant on given inputs.
     *
     * @param string ...$parameters
     * @return \Axlon\PostalCodeValidation\Rules\PostalCode
     */
    public static function with(string ...$parameters)
    {
        return new static($parameters, true);
    }

    /**
     * Convert the rule to a validation string.
     *
     * @return string
     */
    public function __toString()
    {
        return 'postal_code' . ($this->dependant ? '_with:' : ':') . implode(',', $this->parameters);
    }
}
