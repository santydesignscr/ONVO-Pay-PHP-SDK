<?php

namespace ONVO\Models;

use DateTime;

class ShippingRate
{
    public ?string $id;
    public ?DateTime $createdAt;
    public int $amount; // >= 0
    public string $currency; // "USD" | "CRC"
    public string $displayName;
    public ?bool $isActive;
    public ?DeliveryEstimate $deliveryEstimate;
    public ?DateTime $updatedAt;

    public function __construct(
        ?int $amount = null,
        ?string $currency = '',
        ?string $displayName = '',
        ?bool $isActive = true,
        ?DeliveryEstimate $deliveryEstimate = null,
        ?string $id = null,
        ?DateTime $createdAt = null,
        ?DateTime $updatedAt = null
    ) {
        $this->id = $id;
        $this->createdAt = $createdAt;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->displayName = $displayName;
        $this->isActive = $isActive;
        $this->deliveryEstimate = $deliveryEstimate;
        $this->updatedAt = $updatedAt;
    }

    public function setData(array $data)
    {
        if (isset($data['id'])) {
            $this->id = $data['id'];
        }

        if (isset($data['createdAt'])) {
            $this->createdAt = new DateTime($data['createdAt']);
        }

        if (isset($data['amount'])) {
            $this->amount = $data['amount'];
        }

        if (isset($data['currency'])) {
            $this->currency = $data['currency'];
        }

        if (isset($data['displayName'])) {
            $this->displayName = $data['displayName'];
        }

        if (isset($data['isActive'])) {
            $this->isActive = $data['isActive'];
        }

        if (isset($data['deliveryEstimate'])) {
            $this->deliveryEstimate = new DeliveryEstimate(
                $data['deliveryEstimate']['minimumUnit'],
                $data['deliveryEstimate']['minimumValue'],
                $data['deliveryEstimate']['maximumUnit'],
                $data['deliveryEstimate']['maximumValue']
            );
        }

        if (isset($data['updatedAt'])) {
            $this->updatedAt = new DateTime($data['updatedAt']);
        }
    }

    public function toJson(bool $newRate = false): string
    {
        return json_encode($this->toArray($newRate));
    }

    public function toArray(bool $newRate = false): array
    {
        if ($newRate) {
            $data = [
                'amount' => $this->amount,
                'currency' => $this->currency,
                'displayName' => $this->displayName,
            ];
            if ($this->isActive !== null) {
                $data['isActive'] = $this->isActive;
            }
            if ($this->deliveryEstimate !== null) {
                $data['deliveryEstimate'] = $this->deliveryEstimate->toArray();
            }
            return $data;
        }

        return [
            'id' => $this->id,
            'createdAt' => $this->createdAt?->format(DateTime::ATOM),
            'amount' => $this->amount,
            'currency' => $this->currency,
            'displayName' => $this->displayName,
            'isActive' => $this->isActive,
            'deliveryEstimate' => $this->deliveryEstimate?->toArray(),
            'updatedAt' => $this->updatedAt?->format(DateTime::ATOM),
        ];
    }

    public function toUpdateArray(): array
    {
        $data = [];
        if ($this->amount !== null) {
            $data['amount'] = $this->amount;
        }
        if ($this->currency !== null) {
            $data['currency'] = $this->currency;
        }
        if ($this->displayName !== null) {
            $data['displayName'] = $this->displayName;
        }
        if ($this->isActive !== null) {
            $data['isActive'] = $this->isActive;
        }
        if ($this->deliveryEstimate !== null) {
            $data['deliveryEstimate'] = $this->deliveryEstimate->toArray();
        }
        return $data;
    }

    public function toUpdateJson(): string
    {
        return json_encode($this->toUpdateArray());
    }
}

class DeliveryEstimate
{
    public string $minimumUnit; // "hours" | "days"
    public int $minimumValue;
    public string $maximumUnit;
    public int $maximumValue;

    public function __construct(
        ?string $minimumUnit = '',
        ?int $minimumValue = null,
        ?string $maximumUnit = '',
        ?int $maximumValue = null
    ) {
        $this->minimumUnit = $minimumUnit;
        $this->minimumValue = $minimumValue;
        $this->maximumUnit = $maximumUnit;
        $this->maximumValue = $maximumValue;
    }

    public function setData(array $data)
    {
        if (isset($data['minimumUnit'])) {
            $this->minimumUnit = $data['minimumUnit'];
        }

        if (isset($data['minimumValue'])) {
            $this->minimumValue = $data['minimumValue'];
        }

        if (isset($data['maximumUnit'])) {
            $this->maximumUnit = $data['maximumUnit'];
        }

        if (isset($data['maximumValue'])) {
            $this->maximumValue = $data['maximumValue'];
        }
    }

    public function toArray(): array
    {
        return [
            'minimumUnit' => $this->minimumUnit,
            'minimumValue' => $this->minimumValue,
            'maximumUnit' => $this->maximumUnit,
            'maximumValue' => $this->maximumValue,
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }
}