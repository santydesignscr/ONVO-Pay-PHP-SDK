<?php
namespace ONVO\Models\Events;

use ONVO\Models\Events\Value\Customer;
use ONVO\Models\Events\Value\Metadata;
use ONVO\Models\Events\Value\ErrorDetail;

class PaymentIntentFailedEvent extends BaseEvent
{
    public string      $id;
    public string      $accountId;
    public string      $currency;
    public string      $status;
    public ?Customer   $customer;
    public ?Metadata   $metadata;
    public ErrorDetail $error;

    protected function validate(): void
    {
        $d = $this->rawData;
        foreach (['id','accountId','currency','status','error'] as $f) {
            if (!array_key_exists($f, $d)) {
                throw new \InvalidArgumentException("Falta campo «{$f}» en payment-intent.failed");
            }
        }

        $this->id        = $d['id'];
        $this->accountId = $d['accountId'];
        $this->currency  = $d['currency'];
        $this->status    = $d['status'];
        $this->customer  = !empty($d['customer']) ? Customer::fromArray($d['customer']) : null;
        $this->metadata  = !empty($d['metadata']) ? Metadata::fromArray($d['metadata']) : null;
        $this->error     = ErrorDetail::fromArray($d['error']);
    }
}