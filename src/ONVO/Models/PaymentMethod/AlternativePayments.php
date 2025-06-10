<?php

namespace ONVO\Models\PaymentMethod;

class MobileNumber
{
    public string $maskedNumber;
    public ?string $identification;
    public ?int $identificationType;
    public ?string $number;

    public function __construct(
        ?string $maskedNumber = '',
        ?string $identification = null,
        ?int $identificationType = null,
        ?string $number = null
    ) {
        $this->maskedNumber = $maskedNumber;
        $this->identification = $identification;
        $this->identificationType = $identificationType;
        $this->number = $number;
    }

    public function setData(array $data)
    {
        if (isset($data['maskedNumber'])) {
            $this->maskedNumber = $data['maskedNumber'];
        }

        if (isset($data['identification'])) {
            $this->identification = $data['identification'];
        }

        if (isset($data['identificationType'])) {
            $this->identificationType = $data['identificationType'];
        }

        if (isset($data['number'])) {
            $this->number = $data['number'];
        }
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
        ?string $pin = null,
        ?string $phoneNumber = null
    ) {
        $this->pin = $pin;
        $this->phoneNumber = $phoneNumber;
    }

    public function setData(array $data)
    {
        if (isset($data['pin'])) {
            $this->pin = $data['pin'];
        }

        if (isset($data['phoneNumber'])) {
            $this->phoneNumber = $data['phoneNumber'];
        }
    }

    public function toArray(): array
    {
        return [
            'pin' => $this->pin,
            'phoneNumber' => $this->phoneNumber,
        ];
    }
}