<?php

namespace ONVO\Services;

use ONVO\Http\Client;
use ONVO\Models\Client as Customer;
use ONVO\Models\PaymentMethod\PaymentMethod;

class CustomersService
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Create a new customer
     *
     * @param array $data Customer creation parameters
     * @return Customer
     */
    public function create(array $data): Customer
    {
        $response = $this->client->post('/customers', $data);
        
        // Crear y retornar una instancia de Customer con los datos de la respuesta
        return $this->mapResponseToCustomer($response);
    }

    /**
     * List customers with optional filtering
     *
     * @param array|null $params Optional parameters for filtering
     * @return array Array containing list of customers and pagination metadata
     */
    public function list(?array $params = []): array
    {
        $response = $this->client->get('/customers', $params, true);
        
        $customers = [];
        foreach ($response['data'] as $customerData) {
            $customers[] = $this->mapResponseToCustomer($customerData);
        }
        
        return [
            'data' => $customers,
            'hasMore' => $response['hasMore'] ?? false,
            'totalCount' => $response['totalCount'] ?? count($customers),
        ];
    }

    /**
     * Retrieve a customer by ID
     *
     * @param string $id Customer ID
     * @return Customer
     */
    public function retrieve(string $id): Customer
    {
        $response = $this->client->get("/customers/{$id}");
        
        return $this->mapResponseToCustomer($response);
    }

    /**
     * Update a customer
     *
     * @param string $id Customer ID
     * @param array $data Customer update parameters
     * @return Customer
     */
    public function update(string $id, array $data): Customer
    {
        $response = $this->client->post("/customers/{$id}", $data);
        
        return $this->mapResponseToCustomer($response);
    }

    /**
     * Delete a customer
     *
     * @param string $id Customer ID
     * @return array Deleted customer response
     */
    public function delete(string $id): array
    {
        return $this->client->delete("/customers/{$id}");
    }

    /**
     * List payment methods for a customer
     *
     * @param string $customerId Customer ID
     * @param array|null $params Optional parameters for filtering
     * @return array Array containing list of payment methods and pagination metadata
     */
    public function listPaymentMethods(string $customerId, ?array $params = []): array
    {
        $response = $this->client->get("/customers/{$customerId}/payment-methods", $params, true);
        
        $paymentMethods = [];
        foreach ($response['data'] as $paymentMethodData) {
            $paymentMethods[] = $this->mapResponseToPaymentMethod($paymentMethodData);
        }
        
        return [
            'data' => $paymentMethods,
            'hasMore' => $response['hasMore'] ?? false,
            'totalCount' => $response['totalCount'] ?? count($paymentMethods),
        ];
    }

    /**
     * Map API response to Customer object
     *
     * @param array $data Response data
     * @return Customer
     */
    private function mapResponseToCustomer(array $data): Customer
    {
        // Crear instancias de objetos relacionados si existen en la respuesta
        $address = null;
        if (isset($data['address'])) {
            $address = new \ONVO\Models\Address(
                $data['address']['line1'] ?? null,
                $data['address']['line2'] ?? null,
                $data['address']['city'] ?? null,
                $data['address']['state'] ?? null,
                $data['address']['postalCode'] ?? null,
                $data['address']['country'] ?? null
            );
        }

        $shipping = null;
        if (isset($data['shipping'])) {
            $shippingAddress = null;
            if (isset($data['shipping']['address'])) {
                $shippingAddress = new \ONVO\Models\Address(
                    $data['shipping']['address']['line1'] ?? null,
                    $data['shipping']['address']['line2'] ?? null,
                    $data['shipping']['address']['city'] ?? null,
                    $data['shipping']['address']['state'] ?? null,
                    $data['shipping']['address']['postalCode'] ?? null,
                    $data['shipping']['address']['country'] ?? null
                );
            }
            
            $shipping = new \ONVO\Models\Shipping(
                $data['shipping']['name'] ?? null,
                $data['shipping']['phone'] ?? null,
                $shippingAddress
            );
        }

        // Convertir fechas si existen
        $createdAt = isset($data['createdAt']) ? new \DateTime($data['createdAt']) : null;
        $lastTransactionAt = isset($data['lastTransactionAt']) ? new \DateTime($data['lastTransactionAt']) : null;
        $updatedAt = isset($data['updatedAt']) ? new \DateTime($data['updatedAt']) : null;

        // Crear y retornar el objeto Customer
        return new Customer(
            $data['id'] ?? null,
            $data['amountSpent'] ?? null,
            $createdAt,
            $lastTransactionAt,
            $data['mode'] ?? null,
            $updatedAt,
            $address,
            $data['description'] ?? null,
            $data['email'] ?? null,
            $data['name'] ?? null,
            $data['phone'] ?? null,
            $shipping,
            $data['transactionsCount'] ?? null
        );
    }

    /**
     * Map API response to PaymentMethod object
     *
     * @param array $data Response data
     * @return PaymentMethod
     */
    private function mapResponseToPaymentMethod(array $data): PaymentMethod
    {
        // Implementar la lógica para mapear la respuesta a un objeto PaymentMethod
        // Similar a mapResponseToCustomer pero para PaymentMethod
        
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