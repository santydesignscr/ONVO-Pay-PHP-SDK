<?php

namespace ONVO\Models\PaymentMethod;

class Card
{
    public string $brand; // Enum: "visa" | "mastercard"
    public int $expMonth;
    public int $expYear;
    public string $last4;
    public ?string $cvv; // Nuevo campo para actualización

    public function __construct(
        ?string $brand = '',
        ?int $expMonth = null,
        ?int $expYear = null,
        ?string $last4 = '',
        ?string $cvv = null
    ) {
        $this->brand = $brand;
        $this->expMonth = $expMonth;
        $this->expYear = $expYear;
        $this->last4 = $last4;
        $this->cvv = $cvv;
    }

    public function setData(array $data)
    {
        if (isset($data['brand'])) {
            $this->brand = $data['brand'];
        }

        if (isset($data['expMonth'])) {
            $this->expMonth = $data['expMonth'];
        }

        if (isset($data['expYear'])) {
            $this->expYear = $data['expYear'];
        }

        if (isset($data['last4'])) {
            $this->last4 = $data['last4'];
        }

        if (isset($data['cvv'])) {
            $this->cvv = $data['cvv'];
        }
    }

    public function toArray(): array
    {
        $data = [
            'brand' => $this->brand,
            'expMonth' => $this->expMonth,
            'expYear' => $this->expYear,
            'last4' => $this->last4,
        ];

        // Solo incluir el CVV si está presente (para actualizaciones)
        if ($this->cvv !== null) {
            $data['cvv'] = $this->cvv;
        }

        return $data;
    }
}