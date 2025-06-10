<?php
namespace ONVO\Http;

use ONVO\Exceptions\OnvoException;
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
     * @throws OnvoException
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
     * @throws OnvoException
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
     * @throws OnvoException
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
     * @throws OnvoException
     */
    private function handleResponse($response)
    {
        $body = (string) $response->getBody();
        if (!$this->isJson($body)) {
            throw new OnvoException(
                $response->getStatusCode(),
                null,
                ['Invalid JSON response from API'],
                'Invalid Response'
            );
        }
    
        return json_decode($body, true);
    }

    /**
     * Handle exceptions from Guzzle.
     *
     * @param \GuzzleHttp\Exception\GuzzleException $e The exception.
     * @return OnvoException The ONVO exception.
     */
    private function handleException(GuzzleException $e)
    {
        $statusCode = 0;
        $messages = ['Unexpected error'];
        $apiCode = null;
        $error = 'Unknown Error';
    
        if ($e instanceof ConnectException) {
            $messages = ['Connection failed: ' . $e->getMessage()];
            $error = 'Connection Error';
            $statusCode = 0; // network-level error
        } elseif ($e instanceof ClientException || $e instanceof ServerException) {
            $response = $e->getResponse();
            $statusCode = $response ? $response->getStatusCode() : 0;
            $body = $response ? (string) $response->getBody() : null;
        
            if ($body && $this->isJson($body)) {
                $data = json_decode($body, true);
                
                // Extraer información del error según la estructura de ONVO
                if (isset($data['message'])) {
                    $messages = is_array($data['message']) ? $data['message'] : [$data['message']];
                } else {
                    $messages = [$e->getMessage()];
                }
                
                $apiCode = $data['apiCode'] ?? null;
                $error = $data['error'] ?? $this->getHttpErrorName($statusCode);
            } else {
                $messages = [$e->getMessage()];
                $error = $this->getHttpErrorName($statusCode);
            }
        } elseif ($e instanceof RequestException) {
            $response = $e->getResponse();
            $statusCode = $response ? $response->getStatusCode() : 0;
            $messages = [$e->getMessage()];
            $error = $this->getHttpErrorName($statusCode);
        } else {
            $messages = [$e->getMessage()];
        }
    
        return new OnvoException($statusCode, $apiCode, $messages, $error);
    }
    
    /**
     * Get HTTP error name based on status code.
     *
     * @param int $statusCode
     * @return string
     */
    private function getHttpErrorName($statusCode)
    {
        $errorNames = [
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            409 => 'Conflict',
            422 => 'Unprocessable Entity',
            429 => 'Too Many Requests',
            500 => 'Internal Server Error',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
        ];
        
        return $errorNames[$statusCode] ?? 'HTTP Error';
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