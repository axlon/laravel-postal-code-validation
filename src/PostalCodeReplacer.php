<?php

namespace Axlon\PostalCodeValidation;

use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Support\Arr;
use Illuminate\Validation\Validator;

class PostalCodeReplacer
{
    /**
     * The postal code validator.
     *
     * @var \Axlon\PostalCodeValidation\PostalCodeValidator
     */
    protected $postalCodeValidator;

    /**
     * Create a new error message placeholder replacer.
     *
     * @param \Axlon\PostalCodeValidation\PostalCodeValidator $postalCodeValidator
     * @return void
     */
    public function __construct(PostalCodeValidator $postalCodeValidator)
    {
        $this->postalCodeValidator = $postalCodeValidator;
    }

    /**
     * Get the displayable names of the attributes as a list.
     *
     * @param array $attributes
     * @param \Illuminate\Validation\Validator $validator
     * @return string
     */
    protected function getAttributeList(array $attributes, Validator $validator): string
    {
        $attributes = array_map(function (string $attribute) use ($validator) {
            return $validator->getDisplayableAttribute($attribute);
        }, $attributes);

        return implode(', ', $attributes);
    }

    /**
     * Get a list of examples of acceptable postal codes.
     *
     * @param array $codes
     * @return string
     */
    protected function getExampleList(array $codes): string
    {
        $examples = [];

        foreach ($codes as $code) {
            if (!$this->postalCodeValidator->supports($code)) {
                continue;
            }

            if (($example = $this->postalCodeValidator->getExample($code)) !== null) {
                $examples[] = $example;
            }
        }

        return implode(', ', $examples);
    }

    /**
     * Get the values for the given attributes.
     *
     * @param array $attributes
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return array
     */
    protected function getValues(array $attributes, ValidatorContract $validator)
    {
        if (!$validator instanceof Validator) {
            return [];
        }

        return Arr::only($validator->getData(), $attributes);
    }

    /**
     * Replace all placeholders for the postal_code rule.
     *
     * @param string $message
     * @param string $attribute
     * @param string $rule
     * @param array $parameters
     * @param \Illuminate\Validation\Validator $validator
     * @return string
     */
    public function replacePostalCode(string $message,
                                      string $attribute,
                                      string $rule,
                                      array $parameters,
                                      Validator $validator): string
    {
        $replacements = [
            ':attribute' => $validator->getDisplayableAttribute($attribute),
            ':codes' => implode(', ', $parameters),
            ':examples' => $this->getExampleList($parameters),
        ];

        return str_replace(
            array_keys($replacements), array_values($replacements), $message
        );
    }

    /**
     * Replace all placeholders for the postal_code_for rule.
     *
     * @param string $message
     * @param string $attribute
     * @param string $rule
     * @param array $parameters
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return string
     */
    public function replacePostalCodeFor(string $message,
                                         string $attribute,
                                         string $rule,
                                         array $parameters,
                                         Validator $validator): string
    {
        $replacements = [
            ':attribute' => $validator->getDisplayableAttribute($attribute),
            ':codes' => implode(', ', $values = $this->getValues($parameters, $validator)),
            ':examples' => $this->getExampleList($values),
            ':fields' => $this->getAttributeList($parameters, $validator),
        ];

        return str_replace(
            array_keys($replacements), array_values($replacements), $message
        );
    }
}
