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
        string $country,
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