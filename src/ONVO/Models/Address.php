<?php

namespace ONVO\Models;

class Address
{
    public ?string $line1;
    public ?string $line2;
    public ?string $city;
    public string $country;
    public ?string $postalCode;
    public ?string $state;

    public function __construct(
        ?string $city = null,
        ?string $country = null,
        ?string $line1 = null,
        ?string $line2 = null,
        ?string $postalCode = null,
        ?string $state = null
    ) {
        $this->city = $city;
        $this->country = $country;
        $this->line1 = $line1;
        $this->line2 = $line2;
        $this->postalCode = $postalCode;
        $this->state = $state;
    }

    public function setData(array $data)
    {
        if (isset($data['city'])) {
            $this->city = $data['city'];
        }
        if (isset($data['country'])) {
            $this->country = $data['country'];
        }
        if (isset($data['line1'])) {
            $this->line1 = $data['line1'];
        }
        if (isset($data['line2'])) {
            $this->line2 = $data['line2'];
        }
        if (isset($data['postalCode'])) {
            $this->postalCode = $data['postalCode'];
        }
        if (isset($data['state'])) {
            $this->state = $data['state'];
        }
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function toArray(): array
    {
        return [
            'city' => $this->city,
            'country' => $this->country,
            'line1' => $this->line1,
            'line2' => $this->line2,
            'postalCode' => $this->postalCode,
            'state' => $this->state,
        ];
    }
}