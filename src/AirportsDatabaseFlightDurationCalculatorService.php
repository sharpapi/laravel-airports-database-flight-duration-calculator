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
     * Search for airports by name or city.
     *
     * @param string $query The search query (airport name or city)
     * @param int|null $perPage Maximum number of results per page (max 100)
     * @param array $filters Optional filters: country, timezone, iata_assigned, icao_assigned, lid_assigned
     * @return array The search results
     *
     * @throws GuzzleException
     *
     * @api
     */
    public function searchAirports(string $query, ?int $perPage = null, array $filters = []): array
    {
        $params = [
            'name' => $query,
        ];

        if ($perPage !== null) {
            $params['per_page'] = min($perPage, 100); // Max 100 per API spec
        }

        // Merge additional filters
        $params = array_merge($params, $filters);

        $response = $this->makeGetRequest('/airports', $params);

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
            '/airports/iata/' . $iataCode
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
            '/airports/icao/' . $icaoCode
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
        $response = $this->makeGetRequest(
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
     * @param string $countryCode The ISO country code (2 letters)
     * @param int|null $perPage Number of results per page (max 100)
     * @return array The airports in the specified country
     *
     * @throws GuzzleException
     *
     * @api
     */
    public function getAirportsByCountry(string $countryCode, ?int $perPage = null): array
    {
        $params = [
            'country' => $countryCode,
        ];

        if ($perPage !== null) {
            $params['per_page'] = min($perPage, 100);
        }

        $response = $this->makeGetRequest('/airports', $params);

        return json_decode((string) $response->getBody(), true);
    }
}
