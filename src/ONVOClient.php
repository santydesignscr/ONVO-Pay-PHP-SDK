<?php

namespace ONVO;

use ONVO\Http\Client;
use ONVO\Services\CustomersService;
use ONVO\Services\PaymentIntentsService;
use ONVO\Services\PaymentMethodsService;
use ONVO\Services\ProductsService;
use ONVO\Services\PricesService;
use ONVO\Services\RefundsService;
use ONVO\Services\ShippingRatesService;
use ONVO\Services\SubscriptionsService;
use ONVO\Services\WebhookService;

class ONVOClient
{
    /**
     * @var Client HTTP client for API communication
     */
    private Client $httpClient;
    
    /**
     * @var CustomersService
     */
    private ?CustomersService $customers = null;
    
    /**
     * @var PaymentIntentsService
     */
    private ?PaymentIntentsService $paymentIntents = null;
    
    /**
     * @var PaymentMethodsService
     */
    private ?PaymentMethodsService $paymentMethods = null;
    
    /**
     * @var ProductsService
     */
    private ?ProductsService $products = null;
    
    /**
     * @var PricesService
     */
    private ?PricesService $prices = null;
    
    /**
     * @var RefundsService
     */
    private ?RefundsService $refunds = null;
    
    /**
     * @var ShippingRatesService
     */
    private ?ShippingRatesService $shippingRates = null;
    
    /**
     * @var SubscriptionsService
     */
    private ?SubscriptionsService $subscriptions = null;

    /**
     * @var WebhookService
     */
    private ?WebhookService $webhook = null;
    
    /**
     * Initialize the ONVO Pay SDK client
     *
     * @param string $apiKey Your ONVO Pay API key
     * @param string $baseUrl Base URL for the API (optional)
     */
    public function __construct(string $apiKey, string $baseUrl = 'https://api.onvopay.com/v1')
    {
        $this->httpClient = new Client($apiKey, $baseUrl);
    }

    /**
     * Get the webhook service
     *
     * @return WebhookService
     */
    public function webhookHandler(): WebhookService
    {
        if ($this->webhook === null) {
            $this->webhook = new WebhookService();
        }
        
        return $this->webhook;
    }
    
    /**
     * Get the customers service
     *
     * @return CustomersService
     */
    public function customers(): CustomersService
    {
        if ($this->customers === null) {
            $this->customers = new CustomersService($this->httpClient);
        }
        
        return $this->customers;
    }
    
    /**
     * Get the payment intents service
     *
     * @return PaymentIntentsService
     */
    public function paymentIntents(): PaymentIntentsService
    {
        if ($this->paymentIntents === null) {
            $this->paymentIntents = new PaymentIntentsService($this->httpClient);
        }
        
        return $this->paymentIntents;
    }
    
    /**
     * Get the payment methods service
     *
     * @return PaymentMethodsService
     */
    public function paymentMethods(): PaymentMethodsService
    {
        if ($this->paymentMethods === null) {
            $this->paymentMethods = new PaymentMethodsService($this->httpClient);
        }
        
        return $this->paymentMethods;
    }
    
    /**
     * Get the products service
     *
     * @return ProductsService
     */
    public function products(): ProductsService
    {
        if ($this->products === null) {
            $this->products = new ProductsService($this->httpClient);
        }
        
        return $this->products;
    }
    
    /**
     * Get the prices service
     *
     * @return PricesService
     */
    public function prices(): PricesService
    {
        if ($this->prices === null) {
            $this->prices = new PricesService($this->httpClient);
        }
        
        return $this->prices;
    }
    
    /**
     * Get the refunds service
     *
     * @return RefundsService
     */
    public function refunds(): RefundsService
    {
        if ($this->refunds === null) {
            $this->refunds = new RefundsService($this->httpClient);
        }
        
        return $this->refunds;
    }
    
    /**
     * Get the shipping rates service
     *
     * @return ShippingRatesService
     */
    public function shippingRates(): ShippingRatesService
    {
        if ($this->shippingRates === null) {
            $this->shippingRates = new ShippingRatesService($this->httpClient);
        }
        
        return $this->shippingRates;
    }
    
    /**
     * Get the subscriptions service
     *
     * @return SubscriptionsService
     */
    public function subscriptions(): SubscriptionsService
    {
        if ($this->subscriptions === null) {
            $this->subscriptions = new SubscriptionsService($this->httpClient);
        }
        
        return $this->subscriptions;
    }
}