<?php
namespace ONVO\Models\Events;

use ONVO\Models\Events\Value\Customer;
use ONVO\Models\Events\Value\Metadata;

class CheckoutSessionSucceededEvent extends BaseEvent
{
    public string       $mode;
    public string       $paymentStatus;
    public string       $currency;
    public string       $url;
    public int          $amountTotal;
    public ?\DateTime   $createdAt;
    public ?Metadata    $metadata;
    public ?Customer    $customer;
    public array        $lineItems; // array of LineItem-like arrays

    protected function validate(): void
    {
        $d = $this->rawData;
        foreach (['mode','paymentStatus','currency','url','amountTotal'] as $f) {
            if (!array_key_exists($f, $d)) {
                throw new \InvalidArgumentException("Falta campo «{$f}» en checkout-session.succeeded");
            }
        }

        $this->mode          = $d['mode'];
        $this->paymentStatus = $d['paymentStatus'];
        $this->currency      = $d['currency'];
        $this->url           = $d['url'];
        $this->amountTotal   = (int)$d['amountTotal'];
        $this->createdAt     = isset($d['createdAt']) ? new \DateTime($d['createdAt']) : null;
        $this->metadata      = !empty($d['metadata'])   ? Metadata::fromArray($d['metadata']) : null;
        $this->customer      = !empty($d['customer'])   ? Customer::fromArray($d['customer']) : null;
        $this->lineItems     = $d['lineItems'] ?? [];
    }
}