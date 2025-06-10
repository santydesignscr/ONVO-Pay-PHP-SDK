<?php

namespace ONVO\Models;

use ONVO\Models\Product\Product;
use ONVO\Models\Product\Price;
use DateTime;

class CheckoutSession {
    public ?string $id;
    public ?string $accountId;
    public ?string $url;
    public ?DateTime $updatedAt;
    public ?DateTime $createdAt;
    public ?bool $billingAddressCollection;
    public ?bool $allowPromotionCodes;
    public ?string $successUrl;
    public ?string $cancelUrl;
    public ?string $status; // Enum: "open" o "expired"
    public ?array $lineItems;
    public ?string $mode; // Enum: "test" o "live"
    public ?bool $shippingAddressCollection;
    public ?array $shippingCountries;
    public ?array $shippingRates;
    public ?string $paymentStatus; // Enum: "paid" o "unpaid"
    public ?string $paymentIntentId;
    public ?Account $account;

    public function __construct(
        ?string $id = null,
        ?string $accountId = null,
        ?string $url = null,
        ?DateTime $updatedAt = null,
        ?DateTime $createdAt = null,
        ?bool $billingAddressCollection = null,
        ?bool $allowPromotionCodes = null,
        ?string $successUrl = null,
        ?string $cancelUrl = null,
        ?string $status = null,
        ?array $lineItems = null,
        ?string $mode = null,
        ?bool $shippingAddressCollection = null,
        ?array $shippingCountries = null,
        ?array $shippingRates = null,
        ?string $paymentStatus = null,
        ?string $paymentIntentId = null,
        ?Account $account = null
    ) {
        $this->id = $id;
        $this->accountId = $accountId;
        $this->url = $url;
        $this->updatedAt = $updatedAt;
        $this->createdAt = $createdAt;
        $this->billingAddressCollection = $billingAddressCollection;
        $this->allowPromotionCodes = $allowPromotionCodes;
        $this->successUrl = $successUrl;
        $this->cancelUrl = $cancelUrl;
        $this->status = $status;
        $this->lineItems = $lineItems;
        $this->mode = $mode;
        $this->shippingAddressCollection = $shippingAddressCollection;
        $this->shippingCountries = $shippingCountries;
        $this->shippingRates = $shippingRates;
        $this->paymentStatus = $paymentStatus;
        $this->paymentIntentId = $paymentIntentId;
        $this->account = $account;
    }

    public function setData(array $data)
    {
        if (isset($data['id'])) {
            $this->id = $data['id'];
        }
        if (isset($data['accountId'])) {
            $this->accountId = $data['accountId'];
        }
        if (isset($data['url'])) {
            $this->url = $data['url'];
        }
        if (isset($data['updatedAt'])) {
            $this->updatedAt = new DateTime($data['updatedAt']);
        }
        if (isset($data['createdAt'])) {
            $this->createdAt = new DateTime($data['createdAt']);
        }
        if (isset($data['billingAddressCollection'])) {
            $this->billingAddressCollection = (bool)$data['billingAddressCollection'];
        }
        if (isset($data['allowPromotionCodes'])) {
            $this->allowPromotionCodes = (bool)$data['allowPromotionCodes'];
        }
        if (isset($data['successUrl'])) {
            $this->successUrl = $data['successUrl'];
        }
        if (isset($data['cancelUrl'])) {
            $this->cancelUrl = $data['cancelUrl'];
        }
        if (isset($data['status'])) {
            $this->status = $data['status'];
        }
        if (isset($data['lineItems']) && is_array($data['lineItems'])) {
            $this->lineItems = [];
            foreach ($data['lineItems'] as $itemData) {
                $lineItem = new LineItem();
                if (isset($itemData['id'])) {
                    $lineItem->id = $itemData['id'];
                }
                if (isset($itemData['price'])) {
                    $lineItem->price = new Price(); 
                    $lineItem->price->setData($itemData['price']);
                }
                if (isset($itemData['quantity'])) {
                    $lineItem->quantity = (int)$itemData['quantity'];
                }
                if (isset($itemData['priceId'])) {
                    $lineItem->priceId = (int)$itemData['priceId'];
                }
                if (isset($itemData['unitAmount'])) {
                    $lineItem->unitAmount = (int)$itemData['unitAmount'];
                }
                if (isset($itemData['currency'])) {
                    $lineItem->currency = $itemData['currency'];
                }
                if (isset($itemData['description'])) {
                    $lineItem->description = $itemData['description'];
                }
                $this->lineItems[] = $lineItem;
            }
        }
        if (isset($data['mode'])) {
            $this->mode = $data['mode'];
        }
        if (isset($data['shippingAddressCollection'])) {
            $this->shippingAddressCollection = (bool)$data['shippingAddressCollection'];
        }
        if (isset($data['shippingCountries']) && is_array($data['shippingCountries'])) {
            $this->shippingCountries = $data['shippingCountries'];
        }
        if (isset($data['shippingRates']) && is_array($data['shippingRates'])) {
            $this->shippingRates = $data['shippingRates'];
        }
        if (isset($data['paymentStatus'])) {
            $this->paymentStatus = $data['paymentStatus'];
        }
        if (isset($data['paymentIntentId'])) {
            $this->paymentIntentId = $data['paymentIntentId'];
        }
        if (isset($data['account'])) {
            $this->account = new Account();
            $this->account->setData($data['account']);
        }
    }
}

class LineItem {
    public ?string $id;
    public ?Price $price;
    public ?int $quantity;
    public ?int $priceId;
    public ?int $unitAmount;
    public ?string $currency;
    public ?string $description;

    public function __construct(
        ?string $id = null,
        ?Price $price = null,
        ?int $quantity = null,
        ?int $priceId = null,
        ?int $unitAmount = null,
        ?string $currency = null,
        ?string $description = null
    ) {
        $this->id = $id;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->priceId = $priceId;
        $this->unitAmount = $unitAmount;
        $this->currency = $currency;
        $this->description = $description;
    }
}

class Account {
    // Implementación futura si hay datos disponibles
    public function __construct() {
    }

    public function setData(array $data) {}
}