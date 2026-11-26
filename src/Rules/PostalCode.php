<?php

namespace Axlon\PostalCodeValidation\Rules;

use Axlon\PostalCodeValidation\Contracts\PostalCodeValidator;
use Closure;
use Illuminate\Container\Container;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Arr;

final class PostalCode implements ValidationRule, DataAwareRule
{
    /**
     * The data under validation.
     *
     * @var array<mixed>
     */
    private array $data = [];

    /**
     * Create a new rule instance.
     *
     * @param \Axlon\PostalCodeValidation\Contracts\PostalCodeValidator $validator
     * @param list<string> $parameters
     * @return void
     */
    public function __construct(
        private readonly PostalCodeValidator $validator,
        private readonly array $parameters,
    ) {
    }

    /**
     * Get the area codes to validate against.
     *
     * @return array<non-empty-string>
     */
    public function areaCodes(): array
    {
        $areaCodes = [];

        foreach ($this->parameters as $parameter) {
            if (!self::isAreaCode($parameter)) {
                $parameter = Arr::get($this->data, $parameter);

                if (!self::isAreaCode($parameter)) {
                    continue;
                }
            }

            $areaCodes[] = $parameter;
        }

        return array_unique($areaCodes);
    }

    /**
     * Determine if the given value is a valid area code.
     *
     * @param mixed $value
     * @return bool
     * @phpstan-assert-if-true =non-empty-string $value
     */
    private static function isAreaCode(mixed $value): bool
    {
        return is_string($value) && preg_match('/\A[A-Z]{2}\z/', $value) === 1;
    }

    /**
     * Create a new rule instance.
     *
     * @param array<string>|string $parameters
     * @return self
     */
    public static function of(array|string $parameters): self
    {
        return new self(
            Container::getInstance()->make(PostalCodeValidator::class),
            is_array($parameters) ? array_values($parameters) : [$parameters],
        );
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
        if (is_string($value)) {
            foreach ($this->areaCodes() as $areaCode) {
                if ($this->validator->isValid($value, $areaCode)) {
                    return;
                }
            }
        }

        $fail('validation.postal_code')->translate([
            'areas' => implode(', ', $this->areaCodes()),
        ]);
    }
}
