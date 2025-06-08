<?php

namespace ONVO\Services;

use App\Models\RecurringCharge;
use App\Models\RecurringItem;
use ONVO\Http\Client;

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
     */
    public function create(array $data): RecurringCharge
    {
        $response = $this->client->post('/subscriptions', $data);
        
        return $this->mapResponseToRecurringCharge($response);
    }

    /**
     * List recurring charges with optional filtering
     *
     * @param array|null $params Optional parameters for filtering
     * @return array Array containing list of recurring charges and pagination metadata
     */
    public function list(?array $params = []): array
    {
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
    }

    /**
     * Retrieve a recurring charge by ID
     *
     * @param string $id Recurring charge ID
     * @return RecurringCharge
     */
    public function retrieve(string $id): RecurringCharge
    {
        $response = $this->client->get("/subscriptions/{$id}");
        
        return $this->mapResponseToRecurringCharge($response);
    }

    /**
     * Update a recurring charge
     *
     * @param string $id Recurring charge ID
     * @param array $data Recurring charge update parameters
     * @return RecurringCharge
     */
    public function update(string $id, array $data): RecurringCharge
    {
        $response = $this->client->post("/subscriptions/{$id}", $data);
        
        return $this->mapResponseToRecurringCharge($response);
    }

    /**
     * Cancel a recurring charge
     *
     * @param string $id Recurring charge ID
     * @param array|null $params Optional parameters for cancellation
     * @return array Deleted recurring charge response
     */
    public function cancel(string $id, ?array $params = []): array
    {
        return $this->client->post("/subscriptions/{$id}/cancel", $params);
    }

    /**
     * Confirm a recurring charge
     *
     * @param string $id Recurring charge ID
     * @param array|null $params Optional parameters for confirmation
     * @return RecurringCharge
     */
    public function confirm(string $id, ?array $params = []): RecurringCharge
    {
        $response = $this->client->post("/subscriptions/{$id}/confirm", $params);
        
        return $this->mapResponseToRecurringCharge($response);
    }

    /**
     * Add an item to a recurring charge
     *
     * @param string $subscriptionId Recurring charge ID
     * @param array $data Item creation parameters
     * @return RecurringItem
     */
    public function addItem(string $subscriptionId, array $data): RecurringItem
    {
        $response = $this->client->post("/subscriptions/{$subscriptionId}/items", $data);
        
        return $this->mapResponseToRecurringItem($response);
    }

    /**
     * Update an item in a recurring charge
     *
     * @param string $subscriptionId Recurring charge ID
     * @param string $itemId Item ID
     * @param array $data Item update parameters
     * @return RecurringItem
     */
    public function updateItem(string $subscriptionId, string $itemId, array $data): RecurringItem
    {
        $response = $this->client->post("/subscriptions/{$subscriptionId}/items/{$itemId}", $data);
        
        return $this->mapResponseToRecurringItem($response);
    }

    /**
     * Remove an item from a recurring charge
     *
     * @param string $subscriptionId Recurring charge ID
     * @param string $itemId Item ID
     * @return void
     */
    public function removeItem(string $subscriptionId, string $itemId): void
    {
        $this->client->delete("/subscriptions/{$subscriptionId}/items/{$itemId}");
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
        return new RecurringCharge($data);
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