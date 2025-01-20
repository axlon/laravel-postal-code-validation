<?php

declare(strict_types=1);

namespace Axlon\PostalCodeValidation\Rules;

use Axlon\PostalCodeValidation\PostalCodeValidator;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use InvalidArgumentException;

final class PostalCode implements ValidationRule, DataAwareRule
{
    /**
     * Create a new validation rule instance.
     *
     * @param array<string> $countryCodes
     * @param array<mixed> $data
     * @return void
     */
    private function __construct(
        private array $countryCodes,
        private array $data = [],
    ) {
    }

    /**
     * Determine if the given value is a country code.
     *
     * @param mixed $value
     * @return bool
     * @phpstan-assert-if-true =non-empty-string $value
     */
    private static function isCountryCode(mixed $value): bool
    {
        return is_string($value) && preg_match('/^[A-Z]{2}$/', $value) === 1;
    }

    /**
     * Create a new validation rule instance.
     *
     * @param array<string>|string ...$parameters
     * @return self
     */
    public static function of(array|string ...$parameters): self
    {
        /** @var list<string> $parameters */
        $parameters = Arr::flatten($parameters, depth: 1);

        if ($parameters === []) {
            throw new InvalidArgumentException('Postal code validation requires at least 1 parameter');
        }

        return new self($parameters);
    }

    /**
     * Set the data under validation.
     *
     * @param array<mixed> $data
     * @return $this
     */
    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_null($value)) {
            return;
        }

        if (!is_string($value)) {
            $fail('validation.string')->translate();
        } else {
            $value = mb_strtoupper($value);

            /** @var \Axlon\PostalCodeValidation\PostalCodeValidator $validator */
            $validator = App::make(PostalCodeValidator::class);

            foreach ($this->countryCodes as $countryCode) {
                if (!self::isCountryCode($countryCode)) {
                    $countryCode = Arr::get($this->data, $countryCode);

                    if (!self::isCountryCode($countryCode)) {
                        continue;
                    }
                }

                if ($validator->passes($countryCode, $value)) {
                    return;
                }
            }

            $fail('validation.postal_code')->translate();
        }
    }
}
