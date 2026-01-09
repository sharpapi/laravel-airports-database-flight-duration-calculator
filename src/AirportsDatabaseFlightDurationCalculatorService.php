<?php

declare(strict_types=1);

namespace SharpAPI\AirportsDatabaseFlightDurationCalculator;

use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;
use SharpAPI\Core\Client\SharpApiClient;

/**
 * @api
 */
class AirportsDatabaseFlightDurationCalculatorService extends SharpApiClient
{
    /**
     * Initializes a new instance of the class.
     *
     * @throws InvalidArgumentException if the API key is empty.
     */
    public function __construct()
    {
        parent::__construct(config('sharpapi-airports-database-flight-duration-calculator.api_key'));
        $this->setApiBaseUrl(
            config(
                'sharpapi-airports-database-flight-duration-calculator.base_url',
                'https://sharpapi.com/api/v1'
            )
        );
        $this->setUserAgent('SharpAPILaravelAirportsDatabaseFlightDurationCalculator/1.0.0');
    }

    /**
     * Search for airports by name, IATA code, ICAO code, or city.
     *
     * @param string $query The search query (airport name, IATA code, ICAO code, or city)
     * @param int|null $limit Maximum number of results to return
     * @return array The search results
     *
     * @throws GuzzleException
     *
     * @api
     */
    public function searchAirports(string $query, ?int $limit = null): array
    {
        $params = [
            'query' => $query,
        ];

        if ($limit !== null) {
            $params['limit'] = $limit;
        }

        $response = $this->makeRequest(
            'GET',
            '/utility/airports/search',
            $params
        );

        return json_decode((string) $response->getBody(), true);
    }

    /**
     * Get airport details by IATA code.
     *
     * @param string $iataCode The IATA code of the airport
     * @return array The airport details
     *
     * @throws GuzzleException
     *
     * @api
     */
    public function getAirportByIataCode(string $iataCode): array
    {
        $response = $this->makeRequest(
            'GET',
            '/utility/airports/iata/' . $iataCode
        );

        return json_decode((string) $response->getBody(), true);
    }

    /**
     * Get airport details by ICAO code.
     *
     * @param string $icaoCode The ICAO code of the airport
     * @return array The airport details
     *
     * @throws GuzzleException
     *
     * @api
     */
    public function getAirportByIcaoCode(string $icaoCode): array
    {
        $response = $this->makeRequest(
            'GET',
            '/utility/airports/icao/' . $icaoCode
        );

        return json_decode((string) $response->getBody(), true);
    }

    /**
     * Calculate flight duration between two airports.
     *
     * @param string $fromIataCode The IATA code of the departure airport
     * @param string $toIataCode The IATA code of the arrival airport
     * @return array The flight duration details
     *
     * @throws GuzzleException
     *
     * @api
     */
    public function calculateFlightDuration(string $fromIataCode, string $toIataCode): array
    {
        $response = $this->makeRequest(
            'GET',
            '/utility/flight-duration',
            [
                'from' => $fromIataCode,
                'to' => $toIataCode,
            ]
        );

        return json_decode((string) $response->getBody(), true);
    }

    /**
     * Get airports by country code.
     *
     * @param string $countryCode The ISO country code
     * @param int|null $limit Maximum number of results to return
     * @return array The airports in the specified country
     *
     * @throws GuzzleException
     *
     * @api
     */
    public function getAirportsByCountry(string $countryCode, ?int $limit = null): array
    {
        $params = [
            'country' => $countryCode,
        ];

        if ($limit !== null) {
            $params['limit'] = $limit;
        }

        $response = $this->makeRequest(
            'GET',
            '/utility/airports/country',
            $params
        );

        return json_decode((string) $response->getBody(), true);
    }
}
