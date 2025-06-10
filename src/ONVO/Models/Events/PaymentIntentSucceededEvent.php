<?php
namespace ONVO\Models\Events;

use ONVO\Models\Events\Value\Customer;
use ONVO\Models\Events\Value\Metadata;

class PaymentIntentSucceededEvent extends BaseEvent
{
    public string        $id;
    public string        $accountId;
    public string        $currency;
    public int           $amount;
    public string        $status;
    public int           $confirmationAttempts;
    public string        $description;
    public \DateTime     $createdAt;
    public Customer      $customer;
    public ?Metadata     $metadata;

    protected function validate(): void
    {
        $d = $this->rawData;
        foreach (['id','accountId','currency','amount','status','confirmationAttempts','description','createdAt','customer'] as $f) {
            if (!isset($d[$f])) {
                throw new \InvalidArgumentException("Falta campo «{$f}» en payment-intent.succeeded");
            }
        }

        $this->id                   = $d['id'];
        $this->accountId            = $d['accountId'];
        $this->currency             = $d['currency'];
        $this->amount               = (int)$d['amount'];
        $this->status               = $d['status'];
        $this->confirmationAttempts = (int)$d['confirmationAttempts'];
        $this->description          = $d['description'];
        $this->createdAt            = new \DateTime($d['createdAt']);
        $this->customer             = Customer::fromArray($d['customer']);
        $this->metadata             = !empty($d['metadata'])
                                        ? Metadata::fromArray($d['metadata'])
                                        : null;
    }
}