<?php
namespace ONVO\Models\Events;

use ONVO\Models\Events\Value\Customer;
use ONVO\Models\Events\Value\ErrorDetail;

class SubscriptionRenewalFailedEvent extends BaseEvent
{
    public string      $accountId;
    public string      $subscriptionId;
    public ?string     $paymentIntentId;
    public string      $currency;
    public string      $invoiceStatus;
    public string      $subscriptionStatus;
    public ?int        $attemptCount;
    public \DateTime   $invoicePeriodStart;
    public \DateTime   $invoicePeriodEnd;
    public \DateTime   $periodStart;
    public \DateTime   $periodEnd;
    public ?\DateTime  $nextPaymentAttempt;
    public \DateTime   $lastPaymentAttempt;
    public ?Customer   $customer;
    public ErrorDetail $error;

    protected function validate(): void
    {
        $d = $this->rawData;
        $required = [
            'accountId','subscriptionId','currency','invoiceStatus','subscriptionStatus',
            'invoicePeriodStart','invoicePeriodEnd','periodStart','periodEnd','lastPaymentAttempt','error'
        ];
        foreach ($required as $f) {
            if (!array_key_exists($f, $d)) {
                throw new \InvalidArgumentException("Falta campo «{$f}» en subscription.renewal.failed");
            }
        }

        $this->accountId          = $d['accountId'];
        $this->subscriptionId     = $d['subscriptionId'];
        $this->paymentIntentId    = $d['paymentIntentId'] ?? null;
        $this->currency           = $d['currency'];
        $this->invoiceStatus      = $d['invoiceStatus'];
        $this->subscriptionStatus = $d['subscriptionStatus'];
        $this->attemptCount       = isset($d['attemptCount']) ? (int)$d['attemptCount'] : null;
        $this->invoicePeriodStart = new \DateTime($d['invoicePeriodStart']);
        $this->invoicePeriodEnd   = new \DateTime($d['invoicePeriodEnd']);
        $this->periodStart        = new \DateTime($d['periodStart']);
        $this->periodEnd          = new \DateTime($d['periodEnd']);
        $this->nextPaymentAttempt = isset($d['nextPaymentAttempt'])
                                        ? new \DateTime($d['nextPaymentAttempt'])
                                        : null;
        $this->lastPaymentAttempt = new \DateTime($d['lastPaymentAttempt']);
        $this->customer           = !empty($d['customer'])
                                        ? Customer::fromArray($d['customer'])
                                        : null;
        $this->error              = ErrorDetail::fromArray($d['error']);
    }
}