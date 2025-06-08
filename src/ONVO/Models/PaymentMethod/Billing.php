<?php

namespace ONVO\Models\PaymentMethod;

use DateTime;
use ONVO\Models\Address;

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