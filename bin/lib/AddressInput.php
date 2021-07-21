<?php

namespace Axlon\PostalCodeValidation\Build;

use Illuminate\Support\LazyCollection;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AddressInput
{
    /**
     * The HTTP client.
     *
     * @var \Symfony\Contracts\HttpClient\HttpClientInterface
     */
    protected $httpClient;

    /**
     * Create a new libAddressInput API client.
     *
     * @param \Symfony\Contracts\HttpClient\HttpClientInterface $httpClient
     * @return void
     */
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Get all available countries.
     *
     * @return \Illuminate\Support\LazyCollection
     */
    public function all(): LazyCollection
    {
        return LazyCollection::make(function () {
            foreach ($this->keys() as $key) {
                yield $this->httpClient->request('GET', "data/{$key}")->toArray();
            }
        })->map(function (array $country) {
            return $this->expandExamples($country);
        });
    }

    /**
     * Expand the examples inputs to an array.
     *
     * @param array $country
     * @return array
     */
    protected function expandExamples(array $country): array
    {
        if (isset($country['zipex'])) {
            $country['zipex'] = explode(',', $country['zipex']);
        } else {
            $country['zipex'] = [];
        }

        return $country;
    }

    /**
     * Get all the available country keys.
     *
     * @return string[]
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function keys(): array
    {
        $response = $this->httpClient
            ->request('GET', 'data')
            ->toArray();

        return explode('~', $response['countries']);
    }
}
