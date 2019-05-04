<?php

namespace Axlon\PostalCodeValidation;

use InvalidArgumentException;

class Validator
{
    /**
     * List of pattern replacements.
     *
     * @var array
     */
    protected const replacements = [
        ' ' => '\s?',
        '@' => '[a-z]',
        '#' => '\d',
        '*' => '[a-z0-9]',
    ];

    /**
     * Validation pattern cache.
     *
     * @var array
     */
    protected $cache;

    /**
     * List of supported countries and their formats.
     *
     * @var array
     */
    protected $formats;

    /**
     * Create a new postal code validator.
     *
     * @return void
     */
    public function __construct()
    {
        $this->cache = [];
        $this->formats = require __DIR__ . '/../resources/formats.php';
    }

    /**
     * Compile the given formats to a regular expression pattern.
     *
     * @param string ...$formats
     * @return string
     */
    protected function compile(string ...$formats)
    {
        $patterns = [];

        if (!$formats) {
            return '/.*/';
        }

        foreach ($formats as $format) {
            $patterns[] = preg_replace_callback(
                '/#+|@+|\*+|\s+/',
                function ($match) {
                    $token = static::replacements[$match[0][0]];

                    if (trim($match[0]) && ($length = strlen($match[0])) > 1) {
                        return $token . '{' . $length . '}';
                    }

                    return $token;
                },
                $format
            );
        }

        if (count($patterns) === 1) {
            return sprintf('/^%s$/i', $patterns[0]);
        }

        return sprintf('/^(%s)$/i', implode('|', $patterns));
    }

    /**
     * Get the formats for given the country code.
     *
     * @param string $countryCode
     * @return string[]
     */
    public function getFormats(string $countryCode)
    {
        if (!$this->supports($countryCode)) {
            throw new InvalidArgumentException("Unsupported country code $countryCode");
        }

        return $this->formats[strtoupper($countryCode)];
    }

    /**
     * Get the compiled pattern for the given country code.
     *
     * @param string $countryCode
     * @return string
     */
    public function getPattern(string $countryCode)
    {
        if (!$this->supports($countryCode)) {
            throw new InvalidArgumentException("Unsupported country code $countryCode");
        }

        if (array_key_exists($countryCode = strtoupper($countryCode), $this->cache)) {
            return $this->cache[$countryCode];
        }

        return $this->cache[$countryCode] = $this->compile(...$this->formats[$countryCode]);
    }

    /**
     * Determine if the given postal code is valid.
     *
     * @param string $countryCode
     * @param string $postalCode
     * @return bool
     */
    public function isValid(string $countryCode, string $postalCode)
    {
        return (bool)preg_match($this->getPattern($countryCode), $postalCode);
    }

    /**
     * Determine if the given country code is supported.
     *
     * @param string $countryCode
     * @return bool
     */
    public function supports(string $countryCode)
    {
        return array_key_exists(strtoupper($countryCode), $this->formats);
    }
}
