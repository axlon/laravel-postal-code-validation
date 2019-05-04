<?php

namespace Axlon\PostalCodeValidation\Rules;

class PostalCode
{
    /**
     * The rule parameters.
     *
     * @var string[]
     */
    protected $parameters;

    /**
     * Whether or not this is a with rule.
     *
     * @var bool
     */
    protected $with;

    /**
     * Create a new postal code validation rule.
     *
     * @param bool $with
     * @param array $parameters
     * @return void
     */
    protected function __construct(bool $with, array $parameters)
    {
        $this->parameters = $parameters;
        $this->with = $with;
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
        return new static(false, $parameters);
    }

    /**
     * Create a new postal code rule with given inputs.
     *
     * @param string ...$parameters
     * @return \Axlon\PostalCodeValidation\Rules\PostalCode
     */
    public static function with(string ...$parameters)
    {
        return new static(true, $parameters);
    }

    /**
     * Convert the rule to a validation string.
     *
     * @return string
     */
    public function __toString()
    {
        return 'postal_code' . ($this->with ? '_with:' : ':') . implode(',', $this->parameters);
    }
}
