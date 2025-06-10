<?php
namespace ONVO\Models\Events;

class SubscriptionRenewalSucceededEvent extends BaseEvent
{
    public string     $mode;
    public string     $status;
    public string     $currency;
    public string     $description;
    public int        $total;
    public ?\DateTime $periodStart;
    public ?\DateTime $periodEnd;
    public string     $subscriptionId;
    public string     $paymentIntentId;
    public string     $customerId;

    protected function validate(): void
    {
        $d = $this->rawData;
        foreach (['mode','status','currency','description','total','subscriptionId','paymentIntentId','customerId'] as $f) {
            if (!array_key_exists($f, $d)) {
                throw new \InvalidArgumentException("Falta campo «{$f}» en subscription.renewal.succeeded");
            }
        }

        $this->mode            = $d['mode'];
        $this->status          = $d['status'];
        $this->currency        = $d['currency'];
        $this->description     = $d['description'];
        $this->total           = (int)$d['total'];
        $this->periodStart     = isset($d['periodStart']) ? new \DateTime($d['periodStart']) : null;
        $this->periodEnd       = isset($d['periodEnd'])   ? new \DateTime($d['periodEnd'])   : null;
        $this->subscriptionId  = $d['subscriptionId'];
        $this->paymentIntentId = $d['paymentIntentId'];
        $this->customerId      = $d['customerId'];
    }
}