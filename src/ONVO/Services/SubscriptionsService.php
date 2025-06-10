<?php

namespace ONVO\Services;

use ONVO\Models\RecurringCharge;
use ONVO\Models\RecurringItem;
use ONVO\Http\Client;
use ONVO\Exceptions\OnvoException;

class SubscriptionsService
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Create a new recurring charge
     *
     * @param array $data Recurring charge creation parameters
     * @return RecurringCharge
     * @throws OnvoException
     */
    public function create(array $data): RecurringCharge
    {
        try {
            $response = $this->client->post('/subscriptions', $data);
            return $this->mapResponseToRecurringCharge($response);
        } catch (OnvoException $e) {
            throw $e;
        }
    }

    /**
     * List recurring charges with optional filtering
     *
     * @param array|null $params Optional parameters for filtering
     * @return array Array containing list of recurring charges and pagination metadata
     * @throws OnvoException
     */
    public function list(?array $params = []): array
    {
        try {
            $response = $this->client->get('/subscriptions', $params, true);
            
            $recurringCharges = [];
            foreach ($response['data'] as $chargeData) {
                $recurringCharges[] = $this->mapResponseToRecurringCharge($chargeData);
            }
            
            return [
                'data' => $recurringCharges,
                'hasMore' => $response['hasMore'] ?? false,
                'totalCount' => $response['totalCount'] ?? count($recurringCharges),
            ];
        } catch (OnvoException $e) {
            throw $e;
        }
    }

    /**
     * Retrieve a recurring charge by ID
     *
     * @param string $id Recurring charge ID
     * @return RecurringCharge
     * @throws OnvoException
     */
    public function retrieve(string $id): RecurringCharge
    {
        try {
            $response = $this->client->get("/subscriptions/{$id}");
            return $this->mapResponseToRecurringCharge($response);
        } catch (OnvoException $e) {
            throw $e;
        }
    }

    /**
     * Update a recurring charge
     *
     * @param string $id Recurring charge ID
     * @param array $data Recurring charge update parameters
     * @return RecurringCharge
     * @throws OnvoException
     */
    public function update(string $id, array $data): RecurringCharge
    {
        try {
            $response = $this->client->post("/subscriptions/{$id}", $data);
            return $this->mapResponseToRecurringCharge($response);
        } catch (OnvoException $e) {
            throw $e;
        }
    }

    /**
     * Cancel a recurring charge
     *
     * @param string $id Recurring charge ID
     * @param array|null $params Optional parameters for cancellation
     * @return array Deleted recurring charge response
     * @throws OnvoException
     */
    public function cancel(string $id, ?array $params = []): array
    {
        try {
            return $this->client->post("/subscriptions/{$id}/cancel", $params);
        } catch (OnvoException $e) {
            throw $e;
        }
    }

    /**
     * Confirm a recurring charge
     *
     * @param string $id Recurring charge ID
     * @param array|null $params Optional parameters for confirmation
     * @return RecurringCharge
     * @throws OnvoException
     */
    public function confirm(string $id, ?array $params = []): RecurringCharge
    {
        try {
            $response = $this->client->post("/subscriptions/{$id}/confirm", $params);
            return $this->mapResponseToRecurringCharge($response);
        } catch (OnvoException $e) {
            throw $e;
        }
    }

    /**
     * Add an item to a recurring charge
     *
     * @param string $subscriptionId Recurring charge ID
     * @param array $data Item creation parameters
     * @return RecurringItem
     * @throws OnvoException
     */
    public function addItem(string $subscriptionId, array $data): RecurringItem
    {
        try {
            $response = $this->client->post("/subscriptions/{$subscriptionId}/items", $data);
            return $this->mapResponseToRecurringItem($response);
        } catch (OnvoException $e) {
            throw $e;
        }
    }

    /**
     * Update an item in a recurring charge
     *
     * @param string $subscriptionId Recurring charge ID
     * @param string $itemId Item ID
     * @param array $data Item update parameters
     * @return RecurringItem
     * @throws OnvoException
     */
    public function updateItem(string $subscriptionId, string $itemId, array $data): RecurringItem
    {
        try {
            $response = $this->client->post("/subscriptions/{$subscriptionId}/items/{$itemId}", $data);
            return $this->mapResponseToRecurringItem($response);
        } catch (OnvoException $e) {
            throw $e;
        }
    }

    /**
     * Remove an item from a recurring charge
     *
     * @param string $subscriptionId Recurring charge ID
     * @param string $itemId Item ID
     * @return void
     * @throws OnvoException
     */
    public function removeItem(string $subscriptionId, string $itemId): void
    {
        try {
            $this->client->delete("/subscriptions/{$subscriptionId}/items/{$itemId}");
        } catch (OnvoException $e) {
            throw $e;
        }
    }

    /**
     * Map API response to RecurringCharge object
     *
     * @param array $data Response data
     * @return RecurringCharge
     */
    private function mapResponseToRecurringCharge(array $data): RecurringCharge
    {
        // Crear y retornar el objeto RecurringCharge
        $recurringCharge = new RecurringCharge();
        $recurringCharge->setData($data);
        
        return $recurringCharge;
    }

    /**
     * Map API response to RecurringItem object
     *
     * @param array $data Response data
     * @return RecurringItem
     */
    private function mapResponseToRecurringItem(array $data): RecurringItem
    {
        // Crear y retornar el objeto RecurringItem
        return new RecurringItem(
            $data['priceId'] ?? '',
            $data['quantity'] ?? 1
        );
    }
}