<?php

namespace ONVO\Services;

use ONVO\Http\Client;
use ONVO\Models\PaymentMethod\PaymentMethod;
use ONVO\Exceptions\OnvoException;

class PaymentMethodsService
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Create a new payment method
     *
     * @param array $data Payment method creation parameters
     * @return PaymentMethod
     * @throws OnvoException
     */
    public function create(array $data): PaymentMethod
    {
        try {
            $response = $this->client->post('/payment-methods', $data);
            return $this->mapResponseToPaymentMethod($response);
        } catch (OnvoException $e) {
            throw $e;
        }
    }

    /**
     * List payment methods with optional filtering
     *
     * @param array|null $params Optional parameters for filtering
     * @return array Array containing list of payment methods and pagination metadata
     * @throws OnvoException
     */
    public function list(?array $params = []): array
    {
        try {
            $response = $this->client->get('/payment-methods', $params, true);
            
            $paymentMethods = [];
            foreach ($response['data'] as $paymentMethodData) {
                $paymentMethods[] = $this->mapResponseToPaymentMethod($paymentMethodData);
            }
            
            return [
                'data' => $paymentMethods,
                'hasMore' => $response['hasMore'] ?? false,
                'totalCount' => $response['totalCount'] ?? count($paymentMethods),
            ];
        } catch (OnvoException $e) {
            throw $e;
        }
    }

    /**
     * Retrieve a payment method by ID
     *
     * @param string $id Payment method ID
     * @return PaymentMethod
     * @throws OnvoException
     */
    public function retrieve(string $id): PaymentMethod
    {
        try {
            $response = $this->client->get("/payment-methods/{$id}");
            return $this->mapResponseToPaymentMethod($response);
        } catch (OnvoException $e) {
            throw $e;
        }
    }

    /**
     * Update a payment method
     *
     * @param string $id Payment method ID
     * @param array $data Payment method update parameters
     * @return PaymentMethod
     * @throws OnvoException
     */
    public function update(string $id, array $data): PaymentMethod
    {
        try {
            $response = $this->client->post("/payment-methods/{$id}", $data);
            return $this->mapResponseToPaymentMethod($response);
        } catch (OnvoException $e) {
            throw $e;
        }
    }

    /**
     * Detach a payment method
     *
     * @param string $id Payment method ID
     * @return array Detached payment method response
     * @throws OnvoException
     */
    public function detach(string $id): array
    {
        try {
            return $this->client->post("/payment-methods/{$id}/detach");
        } catch (OnvoException $e) {
            throw $e;
        }
    }

    /**
     * Map API response to PaymentMethod object
     *
     * @param array $data Response data
     * @return PaymentMethod
     */
    private function mapResponseToPaymentMethod(array $data): PaymentMethod
    {
        // Convertir fechas si existen
        $createdAt = isset($data['createdAt']) ? new \DateTime($data['createdAt']) : null;
        $updatedAt = isset($data['updatedAt']) ? new \DateTime($data['updatedAt']) : null;

        // Crear objetos relacionados según el tipo de método de pago
        $billing = null;
        if (isset($data['billing'])) {
            $billingAddress = null;
            if (isset($data['billing']['address'])) {
                $billingAddress = new \ONVO\Models\Address(
                    $data['billing']['address']['line1'] ?? null,
                    $data['billing']['address']['line2'] ?? null,
                    $data['billing']['address']['city'] ?? null,
                    $data['billing']['address']['state'] ?? null,
                    $data['billing']['address']['postalCode'] ?? null,
                    $data['billing']['address']['country'] ?? null
                );
            }
            
            $billing = new \ONVO\Models\PaymentMethod\Billing(
                $data['billing']['name'] ?? null,
                $data['billing']['phone'] ?? null,
                $billingAddress
            );
        }

        $card = null;
        if (isset($data['card'])) {
            $card = new \ONVO\Models\PaymentMethod\Card(
                $data['card']['brand'] ?? null,
                $data['card']['country'] ?? null,
                $data['card']['expiryMonth'] ?? null,
                $data['card']['expiryYear'] ?? null,
                $data['card']['last4'] ?? null,
                $data['card']['funding'] ?? null,
                $data['card']['cvv'] ?? null
            );
        }

        // Crear y retornar el objeto PaymentMethod
        return new PaymentMethod(
            $data['id'] ?? '',
            $createdAt,
            $data['mode'] ?? '',
            $data['status'] ?? '',
            $data['type'] ?? '',
            $updatedAt,
            $billing,
            $card,
            $data['customerId'] ?? null,
            null, // mobileNumber
            null  // zunify
        );
    }
}