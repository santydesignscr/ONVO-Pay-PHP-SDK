<?php
namespace ONVO\Http;

use ONVO\Exceptions\ApiException;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\RequestException;

class Client
{
    private $client;
    private $apiKey;

    /**
     * Constructor for the Client.
     *
     * @param string $apiKey The API key for authentication.
     * @param string $baseUrl The base URL of the API.
     */
    public function __construct($apiKey, $baseUrl = 'https://api.onvopay.com/v1')
    {
        $this->apiKey = $apiKey;

        $this->client = new GuzzleClient([
            'base_uri' => $baseUrl,
            'headers' => $this->getHeaders()
        ]);
    }

    /**
     * Make a GET request to the API.
     *
     * @param string $endpoint The API endpoint.
     * @param array $params The query parameters.
     * @param bool $usePagination Whether to use pagination.
     * @return array The response data.
     */
    public function get($endpoint, array $params = [], $usePagination = false)
    {
        $options = [
            'query' => $params
        ];

        if ($usePagination) {
            $options['query'] = $this->addPaginationParams($params);
        }

        try {
            $response = $this->client->get($endpoint, $options);
            return $this->handleResponse($response);
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            throw $this->handleException($e);
        }
    }

    /**
     * Make a POST request to the API.
     *
     * @param string $endpoint The API endpoint.
     * @param array $data The request data.
     * @return array The response data.
     */
    public function post($endpoint, array $data = [])
    {
        $options = [
            'json' => $data
        ];

        try {
            $response = $this->client->post($endpoint, $options);
            return $this->handleResponse($response);
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            throw $this->handleException($e);
        }
    }

    /**
     * Make a DELETE request to the API.
     *
     * @param string $endpoint The API endpoint.
     * @return array The response data.
     */
    public function delete($endpoint)
    {
        try {
            $response = $this->client->delete($endpoint);
            return $this->handleResponse($response);
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            throw $this->handleException($e);
        }
    }

    /**
     * Get the headers for the API request.
     *
     * @return array The headers.
     */
    private function getHeaders()
    {
        return [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json'
        ];
    }

    /**
     * Add pagination parameters to the query.
     *
     * @param array $params The existing query parameters.
     * @return array The updated query parameters.
     */
    private function addPaginationParams(array $params)
    {
        $paginationParams = [
            'limit' => $params['limit'] ?? 10,
        ];

        if (isset($params['endingBefore'])) {
            $paginationParams['endingBefore'] = $params['endingBefore'];
        } elseif (isset($params['startingAfter'])) {
            $paginationParams['startingAfter'] = $params['startingAfter'];
        }

        if (isset($params['createdAt'])) {
            $validKeys = ['gt', 'gte', 'lt', 'lte'];
            foreach ($validKeys as $key) {
                if (isset($params['createdAt'][$key])) {
                    // Validate ISO 8601 UTC datetime format
                    $datetime = $params['createdAt'][$key];
                    if ($this->isValidIso8601($datetime)) {
                        $paginationParams["createdAt"][$key] = $datetime;
                    }
                }
            }
        }

        return $paginationParams;
    }

    /**
     * Handle the API response.
     *
     * @param \Psr\Http\Message\ResponseInterface $response The response.
     * @return array The response data.
     */
    private function handleResponse($response)
    {
        $body = (string) $response->getBody();
        if (!$this->isJson($body)) {
            throw new ApiException('Invalid JSON response from API', $response->getStatusCode());
        }
    
        return json_decode($body, true);
    }

    /**
     * Handle exceptions from Guzzle.
     *
     * @param \GuzzleHttp\Exception\GuzzleException $e The exception.
     * @return ApiException The API exception.
     */
    private function handleException(GuzzleException $e)
    {
        $statusCode = 0;
        $message = 'Unexpected error';
    
        if ($e instanceof ConnectException) {
            $message = 'Connection failed: ' . $e->getMessage();
            $statusCode = 0; // network-level error
        } elseif ($e instanceof ClientException || $e instanceof ServerException) {
            $response = $e->getResponse();
            $statusCode = $response ? $response->getStatusCode() : 0;
            $body = $response ? (string) $response->getBody() : null;
        
            if ($body && $this->isJson($body)) {
                $data = json_decode($body, true);
                $message = $data['message'][0] ?? $data['message'] ?? $e->getMessage();
            } else {
                $message = $e->getMessage();
            }
        } elseif ($e instanceof RequestException) {
            $response = $e->getResponse();
            $statusCode = $response ? $response->getStatusCode() : 0;
            $message = $e->getMessage();
        } else {
            $message = $e->getMessage();
        }
    
        return new ApiException($message, $statusCode);
    }
    
    private function isJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    private function isValidIso8601($datetime)
    {
        $d = \DateTime::createFromFormat(\DateTime::ATOM, $datetime);
        return $d && $d->format(\DateTime::ATOM) === $datetime;
    }
}