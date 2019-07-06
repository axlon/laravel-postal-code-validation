<?php

namespace Axlon\PostalCodeValidation\Extensions;

use Axlon\PostalCodeValidation\Validator;
use Countable;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Validator as BaseValidator;

class PostalCodeFor
{
    /**
     * The request data.
     *
     * @var array
     */
    protected $request;

    /**
     * The postal code validator.
     *
     * @var \Axlon\PostalCodeValidation\Validator
     */
    protected $validator;

    /**
     * Create a new PostalCodeFor validator extension.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Axlon\PostalCodeValidation\Validator $validator
     */
    public function __construct(Request $request, Validator $validator)
    {
        $this->request = $request->all();
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
        $examples = [];

        $parameters = array_filter($parameters, function (string $parameter) {
            return Arr::has($this->request, $parameter);
        });

        foreach ($parameters as $parameter) {
            $countryCode = Arr::get($this->request, $parameter);

            if (!$this->validator->supports($countryCode)) {
                continue;
            }

            $countries[] = $countryCode;
            $examples[] = $this->validator->getExample($countryCode);
        }

        $countries = implode(', ', array_unique($countries));
        $examples = implode(', ', array_unique(array_filter($examples)));

        return str_replace([':countries', ':examples'], [$countries, $examples], $message);
    }

    /**
     * Set the request data from the validator.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     */
    protected function setRequestData(ValidatorContract $validator)
    {
        if (!$validator instanceof BaseValidator) {
            return;
        }

        $this->request = $validator->getData();
    }

    /**
     * Validate the given attribute.
     *
     * @param string $attribute
     * @param string|null $value
     * @param string[] $parameters
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return bool
     */
    public function validate(string $attribute, ?string $value, array $parameters, ValidatorContract $validator)
    {
        $this->setRequestData($validator);

        $parameters = array_filter($parameters, function (string $parameter) {
            return $this->verifyExistence($parameter);
        });

        if (empty($parameters)) {
            return true;
        }

        if (!$value) {
            return false;
        }

        foreach ($parameters as $parameter) {
            $countryCode = Arr::get($this->request, $parameter);

            if (!$this->validator->supports($countryCode)) {
                continue;
            }

            if ($this->validator->validate($countryCode, $value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Verify that a referenced attribute exists.
     *
     * @param string $key
     * @return bool
     * @see \Illuminate\Validation\Validator::validateRequired()
     */
    protected function verifyExistence(string $key)
    {
        $value = Arr::get($this->request, $key);

        if (is_null($value)) {
            return false;
        } elseif (is_string($value) && trim($value) === '') {
            return false;
        } elseif ((is_array($value) || $value instanceof Countable) && count($value) < 1) {
            return false;
        }

        return true;
    }
}
