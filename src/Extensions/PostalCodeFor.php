<?php

namespace Axlon\PostalCodeValidation\Extensions;

use Axlon\PostalCodeValidation\PatternMatcher;
use Axlon\PostalCodeValidation\PostalCodeExamples;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Validator;
use InvalidArgumentException;

class PostalCodeFor
{
    use PostalCodeExamples;

    /**
     * The pattern matcher.
     *
     * @var \Axlon\PostalCodeValidation\PatternMatcher
     */
    protected $matcher;

    /**
     * The request data.
     *
     * @var array
     */
    protected $request;

    /**
     * Create a new PostalCodeFor validator extension.
     *
     * @param \Axlon\PostalCodeValidation\PatternMatcher $matcher
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function __construct(PatternMatcher $matcher, Request $request)
    {
        $this->matcher = $matcher;
        $this->request = $request;
    }

    /**
     * Get the value of the given attribute.
     *
     * @param string $attribute
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return string|null
     */
    protected function input(string $attribute, ValidatorContract $validator): ?string
    {
        return Arr::get(
            $validator instanceof Validator ? $validator->getData() : $this->request->all(), $attribute
        );
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
    public function replace(string $message, string $attribute, string $rule, array $parameters): string
    {
        $countries = [];
        $examples = [];

        $parameters = array_filter($parameters, function (string $parameter) {
            return Arr::has($this->request, $parameter);
        });

        foreach ($parameters as $parameter) {
            $countryCode = Arr::get($this->request, $parameter);

            if (($example = $this->exampleFor($countryCode)) === null) {
                continue;
            }

            $countries[] = $countryCode;
            $examples[] = $example;
        }

        $countries = implode(', ', array_unique($countries));
        $examples = implode(', ', array_unique($examples));

        return str_replace([':countries', ':examples'], [$countries, $examples], $message);
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
    public function validate(string $attribute, ?string $value, array $parameters, ValidatorContract $validator): bool
    {
        if (empty($parameters)) {
            throw new InvalidArgumentException('Validation rule postal_code_for requires at least 1 parameter.');
        }

        if (empty($value)) {
            return false;
        }

        foreach ($parameters as $parameter) {
            if (($parameter = $this->input($parameter, $validator)) === null) {
                continue;
            }

            if (!$this->matcher->supports($parameter)) {
                continue;
            }

            if ($this->matcher->passes($parameter, $value)) {
                return true;
            }
        }

        return false;
    }
}
