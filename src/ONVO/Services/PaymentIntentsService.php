<?php

namespace ONVO\Services;

use ONVO\Http\Client;
use ONVO\Models\PaymentIntent;
use ONVO\Models\PaymentError;
use ONVO\Models\Charge;
use ONVO\Models\NextAction;
use ONVO\Models\RedirectToUrl;

class PaymentIntentsService
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Create a new payment intent
     *
     * @param array $data Payment intent creation parameters
     * @return PaymentIntent
     */
    public function create(array $data): PaymentIntent
    {
        $response = $this->client->post('/payment-intents', $data);
        
        return $this->mapResponseToPaymentIntent($response);
    }

    /**
     * List payment intents with optional filtering
     *
     * @param array|null $params Optional parameters for filtering
     * @return array Array containing list of payment intents and pagination metadata
     */
    public function list(?array $params = []): array
    {
        $response = $this->client->get('/payment-intents', $params, true);
        
        $paymentIntents = [];
        foreach ($response['data'] as $paymentIntentData) {
            $paymentIntents[] = $this->mapResponseToPaymentIntent($paymentIntentData);
        }
        
        return [
            'data' => $paymentIntents,
            'hasMore' => $response['hasMore'] ?? false,
            'totalCount' => $response['totalCount'] ?? count($paymentIntents),
        ];
    }

    /**
     * Retrieve a payment intent by ID
     *
     * @param string $id Payment intent ID
     * @return PaymentIntent
     */
    public function retrieve(string $id): PaymentIntent
    {
        $response = $this->client->get("/payment-intents/{$id}");
        
        return $this->mapResponseToPaymentIntent($response);
    }

    /**
     * Confirm a payment intent
     *
     * @param string $id Payment intent ID
     * @param array|null $params Optional parameters for confirmation
     * @return PaymentIntent
     */
    public function confirm(string $id, ?array $params = []): PaymentIntent
    {
        $response = $this->client->post("/payment-intents/{$id}/confirm", $params);
        
        return $this->mapResponseToPaymentIntent($response);
    }

    /**
     * Capture a payment intent
     *
     * @param string $id Payment intent ID
     * @param array|null $params Optional parameters for capture
     * @return PaymentIntent
     */
    public function capture(string $id, ?array $params = []): PaymentIntent
    {
        $response = $this->client->post("/payment-intents/{$id}/capture", $params);
        
        return $this->mapResponseToPaymentIntent($response);
    }

    /**
     * Cancel a payment intent
     *
     * @param string $id Payment intent ID
     * @param array|null $params Optional parameters for cancellation
     * @return PaymentIntent
     */
    public function cancel(string $id, ?array $params = []): PaymentIntent
    {
        $response = $this->client->post("/payment-intents/{$id}/cancel", $params);
        
        return $this->mapResponseToPaymentIntent($response);
    }

    /**
     * Map API response to PaymentIntent object
     *
     * @param array $data Response data
     * @return PaymentIntent
     */
    private function mapResponseToPaymentIntent(array $data): PaymentIntent
    {
        // Convertir fechas si existen
        $createdAt = isset($data['createdAt']) ? new \DateTime($data['createdAt']) : null;
        $updatedAt = isset($data['updatedAt']) ? new \DateTime($data['updatedAt']) : null;

        // Crear objetos relacionados
        $charges = null;
        if (isset($data['charges']) && is_array($data['charges'])) {
            $charges = [];
            foreach ($data['charges'] as $chargeData) {
                $chargeCreatedAt = isset($chargeData['createdAt']) ? new \DateTime($chargeData['createdAt']) : null;
                $charges[] = new Charge(
                    $chargeData['id'] ?? null,
                    $chargeData['amount'] ?? null,
                    $chargeData['currency'] ?? null,
                    $chargeData['status'] ?? null,
                    $chargeCreatedAt
                );
            }
        }

        $lastPaymentError = null;
        if (isset($data['lastPaymentError'])) {
            $lastPaymentError = new PaymentError(
                $data['lastPaymentError']['code'] ?? null,
                $data['lastPaymentError']['message'] ?? null,
                $data['lastPaymentError']['type'] ?? null
            );
        }

        $nextAction = null;
        if (isset($data['nextAction'])) {
            $redirectToUrl = null;
            if (isset($data['nextAction']['redirectToUrl'])) {
                $redirectToUrl = new RedirectToUrl(
                    $data['nextAction']['redirectToUrl']['url'] ?? null,
                    $data['nextAction']['redirectToUrl']['returnUrl'] ?? null
                );
            }
            
            $nextAction = new NextAction(
                $data['nextAction']['type'] ?? null,
                $redirectToUrl
            );
        }

        // Crear y retornar el objeto PaymentIntent
        return new PaymentIntent(
            $data['id'] ?? null,
            $data['amount'] ?? null,
            $data['baseAmount'] ?? null,
            $data['exchangeRate'] ?? null,
            $data['capturableAmount'] ?? null,
            $data['receivedAmount'] ?? null,
            $data['captureMethod'] ?? null,
            $data['currency'] ?? null,
            $createdAt,
            $data['customerId'] ?? null,
            $data['description'] ?? null,
            $charges,
            $lastPaymentError,
            $data['mode'] ?? null,
            $data['status'] ?? null,
            $updatedAt,
            $data['metadata'] ?? null,
            $data['officeId'] ?? null,
            $data['onBehalfOf'] ?? null,
            $nextAction
        );
    }
}