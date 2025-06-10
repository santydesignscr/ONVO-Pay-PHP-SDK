<?php

namespace App\Models\Product;

use DateTime;

class Price
{
    public ?string $id;
    public ?int $unitAmount;
    public ?string $currency; // Enum: "USD" | "CRC"
    public ?DateTime $createdAt;
    public ?string $nickname;
    public bool $isActive;
    public string $productId;
    public ?Recurring $recurring;
    public ?string $mode; // Enum: "test" | "live"
    public string $type; // Enum: "one_time" | "recurring"
    public ?DateTime $updatedAt;

    public function __construct(
        ?int $unitAmount = null,
        ?string $currency = '',
        ?bool $isActive = null,
        ?string $productId = null,
        ?string $type = '',
        ?Recurring $recurring = null,
        ?string $nickname = null,
        ?string $id = null,
        ?DateTime $createdAt = null,
        ?string $mode = 'test',
        ?DateTime $updatedAt = null
    ) {
        $this->id = $id;
        $this->unitAmount = $unitAmount;
        $this->currency = $currency;
        $this->createdAt = $createdAt;
        $this->nickname = $nickname;
        $this->isActive = $isActive;
        $this->productId = $productId;
        $this->recurring = $recurring;
        $this->mode = $mode;
        $this->type = $type;
        $this->updatedAt = $updatedAt;
    }

    public function setData(array $data)
    {
        if (isset($data['id'])) {
            $this->id = $data['id'];
        }
        if (isset($data['unitAmount'])) {
            $this->unitAmount = $data['unitAmount'];
        }
        if (isset($data['currency'])) {
            $this->currency = $data['currency'];
        }
        if (isset($data['createdAt'])) {
            $this->createdAt = new DateTime($data['createdAt']);
        }
        if (isset($data['nickname'])) {
            $this->nickname = $data['nickname'];
        }
        if (isset($data['isActive'])) {
            $this->isActive = $data['isActive'];
        }
        if (isset($data['productId'])) {
            $this->productId = $data['productId'];
        }
        if (isset($data['recurring'])) {
            $this->recurring = new Recurring($data['recurring']['interval'], $data['recurring']['intervalCount']);
        }
        if (isset($data['mode'])) {
            $this->mode = $data['mode'];
        }
        if (isset($data['type'])) {
            $this->type = $data['type'];
        }
        if (isset($data['updatedAt'])) {
            $this->updatedAt = new DateTime($data['updatedAt']);
        }
    }
    
    public function toJson(bool $newPrice = false): string
    {
        return json_encode($this->toArray($newPrice));
    }

    public function toArray(bool $newPrice = false): array
    {
        if ($newPrice) {
            $data = [
                'unitAmount' => $this->unitAmount,
                'currency' => $this->currency,
                'isActive' => $this->isActive,
                'productId' => $this->productId,
                'type' => $this->type,
            ];
            if ($this->nickname !== null) {
                $data['nickname'] = $this->nickname;
            }
            if ($this->recurring !== null) {
                $data['recurring'] = $this->recurring->toArray();
            }
            return $data;
        }

        return [
            'id' => $this->id,
            'unitAmount' => $this->unitAmount,
            'currency' => $this->currency,
            'createdAt' => $this->createdAt?->format(DateTime::ATOM),
            'nickname' => $this->nickname,
            'isActive' => $this->isActive,
            'productId' => $this->productId,
            'recurring' => $this->recurring?->toArray(),
            'mode' => $this->mode,
            'type' => $this->type,
            'updatedAt' => $this->updatedAt?->format(DateTime::ATOM),
        ];
    }

    public function toUpdateArray(): array
    {
        $data = [];
        if ($this->unitAmount !== null) {
            $data['unitAmount'] = $this->unitAmount;
        }
        if ($this->currency !== null) {
            $data['currency'] = $this->currency;
        }
        if (isset($this->isActive)) {
            $data['isActive'] = $this->isActive;
        }
        if ($this->productId !== null) {
            $data['productId'] = $this->productId;
        }
        if ($this->type !== null) {
            $data['type'] = $this->type;
        }
        if ($this->nickname !== null) {
            $data['nickname'] = $this->nickname;
        }
        if ($this->recurring !== null) {
            $data['recurring'] = $this->recurring->toArray();
        }
        return $data;
    }

    public function toUpdateJson(): string
    {
        return json_encode($this->toUpdateArray());
    }
}

class Recurring
{
    public string $interval; // Ej: "day", "week", "month", "year"
    public int $intervalCount;

    public function __construct(?string $interval = '', ?int $intervalCount = null)
    {
        $this->interval = $interval;
        $this->intervalCount = $intervalCount;
    }

    public function setData(array $data)
    {
        if (isset($data['interval'])) {
            $this->interval = $data['interval'];
        }
        if (isset($data['intervalCount'])) {
            $this->intervalCount = $data['intervalCount'];
        }
    }

    public function toArray(): array
    {
        return [
            'interval' => $this->interval,
            'intervalCount' => $this->intervalCount,
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }
}