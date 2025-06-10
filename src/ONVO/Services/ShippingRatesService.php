<?php

namespace ONVO\Services;

use ONVO\Http\Client;
use ONVO\Models\ShippingRate;
use ONVO\Models\DeliveryEstimate;

class ShippingRatesService
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Create a new shipping rate
     *
     * @param array $data Shipping rate creation parameters
     * @return ShippingRate
     */
    public function create(array $data): ShippingRate
    {
        $response = $this->client->post('/shipping-rates', $data);
        
        return $this->mapResponseToShippingRate($response);
    }

    /**
     * List shipping rates with optional filtering
     *
     * @param array|null $params Optional parameters for filtering
     * @return array Array containing list of shipping rates and pagination metadata
     */
    public function list(?array $params = []): array
    {
        $response = $this->client->get('/shipping-rates', $params, true);
        
        $shippingRates = [];
        foreach ($response['data'] as $shippingRateData) {
            $shippingRates[] = $this->mapResponseToShippingRate($shippingRateData);
        }
        
        return [
            'data' => $shippingRates,
            'hasMore' => $response['hasMore'] ?? false,
            'totalCount' => $response['totalCount'] ?? count($shippingRates),
        ];
    }

    /**
     * Retrieve a shipping rate by ID
     *
     * @param string $id Shipping rate ID
     * @return ShippingRate
     */
    public function retrieve(string $id): ShippingRate
    {
        $response = $this->client->get("/shipping-rates/{$id}");
        
        return $this->mapResponseToShippingRate($response);
    }

    /**
     * Update a shipping rate
     *
     * @param string $id Shipping rate ID
     * @param array $data Shipping rate update parameters
     * @return ShippingRate
     */
    public function update(string $id, array $data): ShippingRate
    {
        $response = $this->client->post("/shipping-rates/{$id}", $data);
        
        return $this->mapResponseToShippingRate($response);
    }

    /**
     * Delete a shipping rate
     *
     * @param string $id Shipping rate ID
     * @return array Deleted shipping rate response
     */
    public function delete(string $id): array
    {
        return $this->client->delete("/shipping-rates/{$id}");
    }

    /**
     * Map API response to ShippingRate object
     *
     * @param array $data Response data
     * @return ShippingRate
     */
    private function mapResponseToShippingRate(array $data): ShippingRate
    {
        // Convertir fechas si existen
        $createdAt = isset($data['createdAt']) ? new \DateTime($data['createdAt']) : null;
        $updatedAt = isset($data['updatedAt']) ? new \DateTime($data['updatedAt']) : null;

        // Crear objetos relacionados
        $deliveryEstimate = null;
        if (isset($data['deliveryEstimate'])) {
            $deliveryEstimate = new DeliveryEstimate(
                $data['deliveryEstimate']['minimumUnit'] ?? '',
                $data['deliveryEstimate']['minimumValue'] ?? 0,
                $data['deliveryEstimate']['maximumUnit'] ?? '',
                $data['deliveryEstimate']['maximumValue'] ?? 0
            );
        }

        // Crear y retornar el objeto ShippingRate
        return new ShippingRate(
            $data['amount'] ?? 0,
            $data['currency'] ?? '',
            $data['displayName'] ?? '',
            $data['isActive'] ?? true,
            $deliveryEstimate,
            $data['id'] ?? null,
            $createdAt,
            $updatedAt
        );
    }
}