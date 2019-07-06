<?php

namespace Axlon\PostalCodeValidation\Tests\Constraints;

use Illuminate\Contracts\Validation\Factory;
use PHPUnit\Framework\Constraint\Constraint;

class Fails extends Constraint
{
    /**
     * @var \Illuminate\Contracts\Validation\Factory
     */
    protected $factory;

    /**
     * @var array
     */
    protected $rules;

    /**
     * Create a new constraint for unsuccessful validation.
     *
     * @param \Illuminate\Contracts\Validation\Factory $factory
     * @param array $rules
     * @return void
     */
    public function __construct(Factory $factory, array $rules)
    {
        parent::__construct();

        $this->factory = $factory;
        $this->rules = $rules;
    }

    /**
     * {@inheritDoc}
     */
    public function evaluate($other, $description = '', $returnResult = false)
    {
        $success = $this->factory->make($other, $this->rules)->fails();

        if ($returnResult) {
            return $success;
        }

        if (!$success) {
            $this->fail($other, $description);
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function toString(): string
    {
        return 'fails validation';
    }
}
