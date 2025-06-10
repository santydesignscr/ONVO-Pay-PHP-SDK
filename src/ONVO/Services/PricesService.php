<?php

namespace ONVO\Services;

use ONVO\Http\Client;
use App\Models\Product\Price;
use App\Models\Product\Recurring;

class PricesService
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Create a new price
     *
     * @param array $data Price creation parameters
     * @return Price
     */
    public function create(array $data): Price
    {
        $response = $this->client->post('/prices', $data);
        
        return $this->mapResponseToPrice($response);
    }

    /**
     * List prices with optional filtering
     *
     * @param array|null $params Optional parameters for filtering
     * @return array Array containing list of prices and pagination metadata
     */
    public function list(?array $params = []): array
    {
        $response = $this->client->get('/prices', $params, true);
        
        $prices = [];
        foreach ($response['data'] as $priceData) {
            $prices[] = $this->mapResponseToPrice($priceData);
        }
        
        return [
            'data' => $prices,
            'hasMore' => $response['hasMore'] ?? false,
            'totalCount' => $response['totalCount'] ?? count($prices),
        ];
    }

    /**
     * Retrieve a price by ID
     *
     * @param string $id Price ID
     * @return Price
     */
    public function retrieve(string $id): Price
    {
        $response = $this->client->get("/prices/{$id}");
        
        return $this->mapResponseToPrice($response);
    }

    /**
     * Update a price
     *
     * @param string $id Price ID
     * @param array $data Price update parameters
     * @return Price
     */
    public function update(string $id, array $data): Price
    {
        $response = $this->client->post("/prices/{$id}", $data);
        
        return $this->mapResponseToPrice($response);
    }

    /**
     * Map API response to Price object
     *
     * @param array $data Response data
     * @return Price
     */
    private function mapResponseToPrice(array $data): Price
    {
        // Convertir fechas si existen
        $createdAt = isset($data['createdAt']) ? new \DateTime($data['createdAt']) : null;
        $updatedAt = isset($data['updatedAt']) ? new \DateTime($data['updatedAt']) : null;

        // Crear objetos relacionados
        $recurring = null;
        if (isset($data['recurring'])) {
            $recurring = new Recurring(
                $data['recurring']['interval'] ?? '',
                $data['recurring']['intervalCount'] ?? 1
            );
        }

        // Crear y retornar el objeto Price
        return new Price(
            $data['unitAmount'] ?? 0,
            $data['currency'] ?? '',
            $data['isActive'] ?? true,
            $data['productId'] ?? '',
            $data['type'] ?? 'one_time',
            $recurring,
            $data['nickname'] ?? null,
            $data['id'] ?? null,
            $createdAt,
            $data['mode'] ?? 'test',
            $updatedAt
        );
    }
}