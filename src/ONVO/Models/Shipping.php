<?php

namespace ONVO\Models;

class Shipping
{
    public Address $address;
    public string $name;
    public ?string $phone;

    public function __construct(
        ?Address $address = null,
        ?string $name = '',
        ?string $phone = null
    ) {
        $this->address = $address;
        $this->name = $name;
        $this->phone = $phone;
    }

    public function setData(array $data)
    {
        if (isset($data['address'])) {
            $this->address = new Address($data['address']);
        }

        if (isset($data['name'])) {
            $this->name = $data['name'];
        }

        if (isset($data['phone'])) {
            $this->phone = $data['phone'];
        }
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function toArray(): array
    {
        return [
            'address' => $this->address->toArray(),
            'name' => $this->name,
            'phone' => $this->phone,
        ];
    }
}