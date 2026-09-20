<?php

declare(strict_types=1);

namespace Axlon\PostalCodeValidation\Rules;

use Axlon\PostalCodeValidation\PostalCodeValidator;
use Closure;
use Illuminate\Container\Container;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Arr;

final class PostalCode implements ValidationRule, DataAwareRule
{
    /**
     * The callback that will generate the "default" version of the rule.
     *
     * @var (\Closure(): self)|null
     */
    private static ?Closure $defaultCallback = null;

    /**
     * The data under validation.
     *
     * @var array<mixed>
     */
    private array $data = [];

    /**
     * The regions to validate against.
     *
     * @var list<non-empty-string>|null
     */
    private ?array $regions = null;

    /**
     * Create a new rule instance.
     *
     * @param \Axlon\PostalCodeValidation\PostalCodeValidator $validator
     * @param array<string> $parameters
     * @return void
     */
    private function __construct(
        private readonly PostalCodeValidator $validator,
        private array $parameters,
    ) {
    }

    /**
     * Determine whether the given value is a valid region code.
     *
     * @param mixed $value
     * @return bool
     * @phpstan-assert-if-true =non-empty-string $value
     */
    private static function isRegionCode(mixed $value): bool
    {
        return is_string($value) && preg_match('/^[A-Z]{2}$/', $value) === 1;
    }

    /**
     * Get the "default" version of the postal code rule.
     *
     * @return self
     */
    public static function default(): self
    {
        return self::of(
            self::$defaultCallback !== null ? (self::$defaultCallback)()->parameters : [],
        );
    }

    /**
     * Set the "default" version of the postal code rule.
     *
     * @param (\Closure(): self)|self|null $callback
     * @return void
     */
    public static function defaults(Closure|self|null $callback): void
    {
        self::$defaultCallback = $callback instanceof self ? static fn () => $callback : $callback;
    }

    /**
     * Create a new rule instance.
     *
     * @param array<string>|string ...$parameters
     * @return self
     */
    public static function of(array|string ...$parameters): self
    {
        $allParameters = [];

        foreach ($parameters as $parameter) {
            $allParameters = [...$allParameters, ...(is_string($parameter) ? [$parameter] : $parameter)];
        }

        return new self(
            Container::getInstance()->make(PostalCodeValidator::class),
            $allParameters,
        );
    }

    /**
     * Get the regions to validate against.
     *
     * @return list<non-empty-string>
     */
    public function regions(): array
    {
        if ($this->regions === null) {
            $regions = [];

            foreach ($this->parameters as $parameter) {
                if (!self::isRegionCode($parameter)) {
                    $parameter = Arr::get($this->data, $parameter);

                    if (!self::isRegionCode($parameter)) {
                        continue;
                    }
                }

                $regions[] = $parameter;
            }

            $this->regions = array_values(array_unique($regions));
        }

        return $this->regions;
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
        $this->regions = null;

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
            foreach ($this->regions() as $region) {
                if ($this->validator->passes($region, $value)) {
                    return;
                }
            }
        }

        $fail('validation.postal_code')->translate([
            'attribute' => $attribute,
            'regions' => implode(', ', $this->regions()),
        ]);
    }
}
