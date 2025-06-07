<?php

namespace ONVO\Models;

use DateTime;
use ONVO\Models\Address;

class PaymentMethod
{
    public string $id;
    public ?Billing $billing;
    public ?Card $card;
    public DateTime $createdAt;
    public ?string $customerId;
    public ?MobileNumber $mobileNumber;
    public ?Zunify $zunify;
    public string $mode; // Enum: "test" | "live"
    public string $status; // Enum: "active" | "detached" | "suspended"
    public string $type; // Enum: "card" | "mobile_number" | "zunify"
    public DateTime $updatedAt;

    public function __construct(
        string $id = '',
        ?DateTime $createdAt = null,
        string $mode = '',
        string $status = '',
        string $type = '',
        ?DateTime $updatedAt = null,
        ?Billing $billing = null,
        ?Card $card = null,
        ?string $customerId = null,
        ?MobileNumber $mobileNumber = null,
        ?Zunify $zunify = null
    ) {
        $this->id = $id;
        $this->billing = $billing;
        $this->card = $card;
        $this->createdAt = $createdAt ?? new DateTime();
        $this->customerId = $customerId;
        $this->mobileNumber = $mobileNumber;
        $this->zunify = $zunify;
        $this->mode = $mode;
        $this->status = $status;
        $this->type = $type;
        $this->updatedAt = $updatedAt ?? new DateTime();
    }

    public function toJson(bool $newPaymentMethod = false): string
    {
        return json_encode($this->toArray($newPaymentMethod));
    }

    public function toArray(bool $newPaymentMethod = false): array
    {
        if ($newPaymentMethod) {
            $data = [
                'type' => $this->type,
            ];

            if ($this->billing !== null) {
                $data['billing'] = $this->billing->toArray();
            }

            if ($this->customerId !== null) {
                $data['customerId'] = $this->customerId;
            }

            // Agregar el objeto correspondiente según el tipo de método de pago
            switch ($this->type) {
                case 'card':
                    if ($this->card !== null) {
                        $data['card'] = $this->card->toArray();
                    }
                    break;
                case 'mobile_number':
                    if ($this->mobileNumber !== null) {
                        $data['mobileNumber'] = $this->mobileNumber->toArray();
                    }
                    break;
                case 'zunify':
                    if ($this->zunify !== null) {
                        $data['zunify'] = $this->zunify->toArray();
                    }
                    break;
            }

            return $data;
        }

        // Para representar un método de pago existente
        return [
            'id' => $this->id,
            'billing' => $this->billing?->toArray(),
            'card' => $this->card?->toArray(),
            'createdAt' => $this->createdAt->format(DateTime::ATOM),
            'customerId' => $this->customerId,
            'mobileNumber' => $this->mobileNumber?->toArray(),
            'zunify' => $this->zunify?->toArray(),
            'mode' => $this->mode,
            'status' => $this->status,
            'type' => $this->type,
            'updatedAt' => $this->updatedAt->format(DateTime::ATOM),
        ];
    }
}

class Billing
{
    public Address $address;
    public string $name;
    public ?string $phone;
    public ?string $email;
    public ?string $idType;
    public ?string $idNumber;

    public function __construct(
        Address $address,
        string $name,
        ?string $phone = null,
        ?string $email = null,
        ?string $idType = null,
        ?string $idNumber = null
    ) {
        $this->address = $address;
        $this->name = $name;
        $this->phone = $phone;
        $this->email = $email;
        $this->idType = $idType;
        $this->idNumber = $idNumber;
    }

    public function toArray(): array
    {
        return [
            'address' => $this->address->toArray(),
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'idType' => $this->idType,
            'idNumber' => $this->idNumber,
        ];
    }
}

class Card
{
    public string $brand; // Enum: "visa" | "mastercard"
    public int $expMonth;
    public int $expYear;
    public string $last4;

    public function __construct(
        string $brand,
        int $expMonth,
        int $expYear,
        string $last4
    ) {
        $this->brand = $brand;
        $this->expMonth = $expMonth;
        $this->expYear = $expYear;
        $this->last4 = $last4;
    }

    public function toArray(): array
    {
        return [
            'brand' => $this->brand,
            'expMonth' => $this->expMonth,
            'expYear' => $this->expYear,
            'last4' => $this->last4,
        ];
    }
}

class MobileNumber
{
    public string $maskedNumber;
    public ?string $identification;
    public ?int $identificationType;
    public ?string $number;

    public function __construct(
        string $maskedNumber = '',
        ?string $identification = null,
        ?int $identificationType = null,
        ?string $number = null
    ) {
        $this->maskedNumber = $maskedNumber;
        $this->identification = $identification;
        $this->identificationType = $identificationType;
        $this->number = $number;
    }

    public function toArray(): array
    {
        $data = [
            'maskedNumber' => $this->maskedNumber,
        ];

        // Para crear un nuevo método de pago tipo mobile_number
        if ($this->identification !== null) {
            $data['identification'] = $this->identification;
        }

        if ($this->identificationType !== null) {
            $data['identificationType'] = $this->identificationType;
        }

        if ($this->number !== null) {
            $data['number'] = $this->number;
        }

        return $data;
    }
}

class Zunify
{
    public string $pin;
    public string $phoneNumber;

    public function __construct(
        string $pin,
        string $phoneNumber
    ) {
        $this->pin = $pin;
        $this->phoneNumber = $phoneNumber;
    }

    public function toArray(): array
    {
        return [
            'pin' => $this->pin,
            'phoneNumber' => $this->phoneNumber,
        ];
    }
}