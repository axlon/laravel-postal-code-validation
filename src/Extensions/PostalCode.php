<?php

namespace Axlon\PostalCodeValidation\Extensions;

use Axlon\PostalCodeValidation\PostalCodeValidator;

class PostalCode
{
    /**
     * The postal code validator.
     *
     * @var \Axlon\PostalCodeValidation\PostalCodeValidator
     */
    protected $validator;

    /**
     * Create a new PostalCode validator extension.
     *
     * @param \Axlon\PostalCodeValidation\PostalCodeValidator $validator
     * @return void
     */
    public function __construct(PostalCodeValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Validate the given attribute.
     *
     * @param string $attribute
     * @param string|null $value
     * @param string[] $parameters
     * @return bool
     */
    public function validate(string $attribute, ?string $value, array $parameters)
    {
        if (!$value) {
            return false;
        }

        if (empty($parameters)) {
            return true;
        }

        foreach ($parameters as $parameter) {
            if (!$this->validator->supports($parameter)) {
                return false;
            }

            if ($this->validator->validate($parameter, $value)) {
                return true;
            }
        }

        return false;
    }
}
