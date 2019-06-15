<?php

namespace Axlon\PostalCodeValidation\Http;

use GuzzleHttp\Client;
use function GuzzleHttp\json_decode;
use function GuzzleHttp\Promise\all;
use Psr\Http\Message\ResponseInterface;

class Broker
{
    protected $client;
    protected $countries;

    /**
     * Create a new broker for Google's Address Data Service.
     *
     * @param \GuzzleHttp\Client $client
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->countries = [];
    }

    /**
     * Get the countries.
     *
     * @return object[]
     */
    public function getCountries()
    {
        if ($this->countries) {
            return $this->countries;
        }

        $queue = [];
        $unwrap = function (ResponseInterface $response) {
            return json_decode($response->getBody()->getContents());
        };

        foreach ($this->getCountryList() as $country) {
            $queue[] = $this->client->getAsync("/ssl-address/data/$country")->then($unwrap);
        }

        return $this->countries = all($queue)->wait();
    }

    /**
     * Get a list of available country codes.
     *
     * @return string[]
     */
    protected function getCountryList()
    {
        $directory = json_decode(
            $this->client->get('/ssl-address/data')->getBody()->getContents()
        );

        return explode('~', $directory->countries);
    }
}
