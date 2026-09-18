<?php

declare(strict_types=1);

namespace Axlon\PostalCodeValidation\Build\Http;

use Webmozart\Assert\Assert;

final class AddressValidationDataClient
{
    /**
     * Get address validation data for all countries.
     *
     * @return non-empty-list<\Axlon\PostalCodeValidation\Build\Http\AddressValidationData>
     * @throws \JsonException
     */
    public function getAllCountries(): array
    {
        $root = $this->sendRequest('');

        Assert::keyExists($root, 'countries');
        Assert::stringNotEmpty($root['countries']);

        return array_map($this->getCountry(...), explode('~', $root['countries']));
    }

    /**
     * Get address validation data for the specified country.
     *
     * @param string $key
     * @return \Axlon\PostalCodeValidation\Build\Http\AddressValidationData
     * @throws \JsonException
     */
    private function getCountry(string $key): AddressValidationData
    {
        Assert::regex($key, '/^[A-Z]{2}$/');

        $response = $this->sendRequest("/$key");

        Assert::keyExists($response, 'key');
        Assert::same($response['key'], $key);

        if (array_key_exists('zip', $response)) {
            Assert::stringNotEmpty($response['zip']);
            Assert::keyExists($response, 'zipex');
            Assert::stringNotEmpty($response['zipex']);

            $zip = [
                'pattern' => $response['zip'],
                'examples' => explode(',', $response['zipex']),
            ];
        }

        return new AddressValidationData($key, $zip ?? null);
    }

    /**
     * Send an HTTP request to the address validation data service.
     *
     * @param string $path
     * @return array<mixed>
     * @throws \JsonException
     */
    private function sendRequest(string $path): array
    {
        $response = file_get_contents(
            "https://chromium-i18n.appspot.com/ssl-address/data$path",
            context: stream_context_create([
                'http' => [
                    'header' => 'Accept: application/json',
                    'method' => 'GET',
                    'timeout' => 10,
                ],
            ]),
        );

        Assert::string($response);
        Assert::isMap($decoded = json_decode(
            $response,
            associative: true,
            flags: JSON_THROW_ON_ERROR,
        ));

        return $decoded;
    }
}
