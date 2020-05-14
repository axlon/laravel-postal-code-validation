<?php

namespace Axlon\PostalCodeValidation\Tests\Constraints;

use Illuminate\Contracts\Validation\Factory;
use Illuminate\Support\Str;
use PHPUnit\Framework\Constraint\Constraint;

class Passes extends Constraint
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
     * Create a new constraint for successful validation.
     *
     * @param \Illuminate\Contracts\Validation\Factory $factory
     * @param array $rules
     * @return void
     */
    public function __construct(Factory $factory, array $rules)
    {
        $this->factory = $factory;
        $this->rules = $rules;
    }

    /**
     * {@inheritDoc}
     */
    protected function additionalFailureDescription($other): string
    {
        $validator = $this->factory->make($other, $this->rules);
        $description = "Rules that did not pass:\r\n";

        foreach ($validator->getMessageBag()->toArray() as $field => $errors) {
            $errors = array_map(function (string $error) {
                return Str::after($error, 'validation.');
            }, $errors);

            $description .= "- $field: " . implode(', ', $errors);
        }

        return $description;
    }

    /**
     * {@inheritDoc}
     */
    public function evaluate($other, $description = '', $returnResult = false)
    {
        $success = !$this->factory->make($other, $this->rules)->fails();

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
        return 'passes validation';
    }
}
