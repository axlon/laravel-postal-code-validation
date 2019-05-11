<?php

namespace Axlon\PostalCodeValidation\Extensions;

use Axlon\PostalCodeValidation\Validator;

class PostalCode
{
    /**
     * The postal code validator.
     *
     * @var \Axlon\PostalCodeValidation\Validator
     */
    protected $validator;

    /**
     * Create a new PostalCode validator extension.
     *
     * @param \Axlon\PostalCodeValidation\Validator $validator
     * @return void
     */
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Replace error message placeholders.
     *
     * @param string $message
     * @param string $attribute
     * @param string $rule
     * @param string[] $parameters
     * @return string
     */
    public function replace(string $message, string $attribute, string $rule, array $parameters)
    {
        $countries = [];
        $formats = [];

        foreach ($parameters as $parameter) {
            if (!$this->validator->supports($parameter)) {
                continue;
            }

            $countries[] = $parameter;
            $formats = array_merge($formats, $this->validator->getFormats($parameter));
        }

        $countries = implode(', ', array_unique($countries));
        $formats = implode(', ', array_unique($formats));

        return str_replace([':countries', ':formats'], [$countries, $formats], $message);
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

        foreach ($parameters as $parameter) {
            if (!$this->validator->supports($parameter)) {
                return false;
            }

            if ($this->validator->isValid($parameter, $value)) {
                return true;
            }
        }

        return false;
    }
}
