<?php

declare(strict_types=1);

namespace Axlon\PostalCodeValidation\Support;

use Axlon\PostalCodeValidation\Contracts\ExampleRepository;

final class PostalCodeExamples implements ExampleRepository
{
    /**
     * The postal code examples.
     *
     * @var array<string, string>|null
     */
    protected ?array $examples = null;

    /**
     * Get a postal code for the specified area.
     *
     * @param string $areaCode
     * @return string|null
     */
    public function get(string $areaCode): ?string
    {
        if ($this->examples === null) {
            /** @var array<string, string> $examples */
            $examples = require __DIR__ . '/../../resources/examples.php';
            $this->examples = $examples;
        }

        return $this->examples[strtoupper($areaCode)] ?? null;
    }
}
