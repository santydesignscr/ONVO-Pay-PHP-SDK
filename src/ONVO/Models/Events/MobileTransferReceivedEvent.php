<?php
namespace ONVO\Models\Events;

class MobileTransferReceivedEvent extends BaseEvent
{
    public int       $amount;
    public string    $currency;
    public ?string   $description;
    public string    $SINPERefNumber;
    public string    $originId;
    public string    $originName;
    public ?string   $originPhone;
    public \DateTime $authorizationDate;

    protected function validate(): void
    {
        $d      = $this->rawData;
        $fields = ['amount','currency','SINPERefNumber','originId','originName','authorizationDate'];
        foreach ($fields as $f) {
            if (!array_key_exists($f, $d)) {
                throw new \InvalidArgumentException("Falta campo «{$f}» en mobile-transfer.received");
            }
        }

        $this->amount            = (int)$d['amount'];
        $this->currency          = $d['currency'];
        $this->description       = $d['description'] ?? null;
        $this->SINPERefNumber    = $d['SINPERefNumber'];
        $this->originId          = $d['originId'];
        $this->originName        = $d['originName'];
        $this->originPhone       = $d['originPhone'] ?? null;
        $this->authorizationDate = new \DateTime($d['authorizationDate']);
    }
}