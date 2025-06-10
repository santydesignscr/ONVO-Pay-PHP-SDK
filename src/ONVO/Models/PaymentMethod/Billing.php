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
        ?Address $address = null,
        ?string $name = '',
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

    public function setData(array $data)
    {
        if (isset($data['address'])) {
            $this->address = new Address();
            $this->address->setData($data['address']);
        }

        if (isset($data['name'])) {
            $this->name = $data['name'];
        }

        if (isset($data['phone'])) {
            $this->phone = $data['phone'];
        }
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