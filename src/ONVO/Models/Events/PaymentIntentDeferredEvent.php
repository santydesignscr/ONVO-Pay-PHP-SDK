<?php
namespace ONVO\Models\Events;

class PaymentIntentDeferredEvent extends BaseEvent
{
    public string    $id;
    public string    $mode;
    public string    $currency;
    public string    $status;
    public int       $confirmationAttempts;
    public string    $description;
    public \DateTime $createdAt;
    public int       $amount;
    public int       $baseAmount;
    public string    $paymentMethodId;
    public string    $customerId;
    public string    $accountId;

    protected function validate(): void
    {
        $d      = $this->rawData;
        $fields = ['id','mode','currency','status','confirmationAttempts',
                   'description','createdAt','amount','baseAmount',
                   'paymentMethodId','customerId','accountId'];
        foreach ($fields as $f) {
            if (!array_key_exists($f, $d)) {
                throw new \InvalidArgumentException("Falta campo «{$f}» en payment-intent.deferred");
            }
        }

        $this->id                   = $d['id'];
        $this->mode                 = $d['mode'];
        $this->currency             = $d['currency'];
        $this->status               = $d['status'];
        $this->confirmationAttempts = (int)$d['confirmationAttempts'];
        $this->description          = $d['description'];
        $this->createdAt            = new \DateTime($d['createdAt']);
        $this->amount               = (int)$d['amount'];
        $this->baseAmount           = (int)$d['baseAmount'];
        $this->paymentMethodId      = $d['paymentMethodId'];
        $this->customerId           = $d['customerId'];
        $this->accountId            = $d['accountId'];
    }
}