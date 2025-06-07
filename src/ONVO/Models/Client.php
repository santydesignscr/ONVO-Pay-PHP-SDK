<?php

namespace ONVO\Models;

use DateTime;

class Client
{
    public ?string $id;
    public ?Address $address;
    public ?int $amountSpent; // en centavos
    public ?string $description;
    public ?DateTime $createdAt;
    public ?string $email;
    public ?DateTime $lastTransactionAt;
    public ?string $mode; // Enum: "test" | "live"
    public ?string $name;
    public ?string $phone;
    public ?Shipping $shipping;
    public ?int $transactionsCount;
    public ?DateTime $updatedAt;

    public function __construct(
        ?string $id = null,
        ?int $amountSpent = null,
        ?DateTime $createdAt = null,
        ?DateTime $lastTransactionAt = null,
        ?string $mode = null,
        ?DateTime $updatedAt = null,
        ?Address $address = null,
        ?string $description = null,
        ?string $email = null,
        ?string $name = null,
        ?string $phone = null,
        ?Shipping $shipping = null,
        ?int $transactionsCount = null
    ) {
        $this->id = $id;
        $this->address = $address;
        $this->amountSpent = $amountSpent;
        $this->description = $description;
        $this->createdAt = $createdAt;
        $this->email = $email;
        $this->lastTransactionAt = $lastTransactionAt;
        $this->mode = $mode;
        $this->name = $name;
        $this->phone = $phone;
        $this->shipping = $shipping;
        $this->transactionsCount = $transactionsCount;
        $this->updatedAt = $updatedAt;
    }

    public function toUpdateJson(): string
    {
        return json_encode($this->toUpdateArray());
    }

    public function toUpdateArray(): array
    {
        $data = [];

        if ($this->address !== null) {
            $data['address'] = $this->address->toArray();
        }

        if ($this->description !== null) {
            $data['description'] = $this->description;
        }

        if ($this->email !== null) {
            $data['email'] = $this->email;
        }

        if ($this->name !== null) {
            $data['name'] = $this->name;
        }

        if ($this->phone !== null) {
            $data['phone'] = $this->phone;
        }

        if ($this->shipping !== null) {
            $data['shipping'] = $this->shipping->toArray();
        }

        return $data;
    }

    public function toJson(bool $newClient = false): string
    {
        return json_encode($this->toArray($newClient));
    }

    public function toArray(bool $newClient = false): array
    {
        if ($newClient) {
            return [
                'address' => $this->address?->toArray(),
                'description' => $this->description,
                'email' => $this->email,
                'name' => $this->name,
                'phone' => $this->phone,
                'shipping' => $this->shipping?->toArray(),
            ];
        }

        return [
            'id' => $this->id,
            'address' => $this->address?->toArray(),
            'amountSpent' => $this->amountSpent,
            'description' => $this->description,
            'createdAt' => $this->createdAt?->format(DateTime::ATOM),
            'email' => $this->email,
            'lastTransactionAt' => $this->lastTransactionAt?->format(DateTime::ATOM),
            'mode' => $this->mode,
            'name' => $this->name,
            'phone' => $this->phone,
            'shipping' => $this->shipping?->toArray(),
            'transactionsCount' => $this->transactionsCount,
            'updatedAt' => $this->updatedAt?->format(DateTime::ATOM),
        ];
    }
}