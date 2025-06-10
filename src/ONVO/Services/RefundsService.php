<?php

namespace ONVO\Services;

use ONVO\Http\Client;
use ONVO\Models\Refund;
use ONVO\Exceptions\OnvoException;

class RefundsService
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Create a new refund
     *
     * @param array $data Refund creation parameters
     * @return Refund
     * @throws OnvoException
     */
    public function create(array $data): Refund
    {
        try {
            $response = $this->client->post('/refunds', $data);
            return $this->mapResponseToRefund($response);
        } catch (OnvoException $e) {
            throw $e;
        }
    }

    /**
     * Retrieve a refund by ID
     *
     * @param string $id Refund ID
     * @return Refund
     * @throws OnvoException
     */
    public function retrieve(string $id): Refund
    {
        try {
            $response = $this->client->get("/refunds/{$id}");
            return $this->mapResponseToRefund($response);
        } catch (OnvoException $e) {
            throw $e;
        }
    }

    /**
     * Map API response to Refund object
     *
     * @param array $data Response data
     * @return Refund
     */
    private function mapResponseToRefund(array $data): Refund
    {
        // Convertir fechas si existen
        $createdAt = isset($data['createdAt']) ? new \DateTime($data['createdAt']) : null;
        $updatedAt = isset($data['updatedAt']) ? new \DateTime($data['updatedAt']) : null;

        // Crear y retornar el objeto Refund
        return new Refund(
            $data['id'] ?? '',
            $data['paymentIntentId'] ?? '',
            $data['amount'] ?? null,
            $data['currency'] ?? null,
            $createdAt,
            $data['description'] ?? null,
            $data['mode'] ?? null,
            $data['status'] ?? null,
            $data['reason'] ?? 'requested_by_customer',
            $updatedAt,
            $data['failureReason'] ?? null
        );
    }
}