<?php

namespace ONVO\Models\PaymentMethod;

use DateTime;

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
        ?string $id = '',
        ?DateTime $createdAt = null,
        ?string $mode = '',
        ?string $status = '',
        ?string $type = '',
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

    public function setData(array $data)
    {
        if (isset($data['id'])) {
            $this->id = $data['id'];
        }

        if (isset($data['billing'])) {
            $this->billing = new Billing();
            $this->billing->setData($data['billing']);
        }

        if (isset($data['card'])) {
            $this->card = new Card();
            $this->card->setData($data['card']);
        }

        if (isset($data['createdAt'])) {
            $this->createdAt = new DateTime($data['createdAt']);
        }

        if (isset($data['customerId'])) {
            $this->customerId = $data['customerId'];
        }
        if (isset($data['mobileNumber'])) {
            $this->mobileNumber = new MobileNumber();
            $this->mobileNumber->setData($data['mobileNumber']);
        }

        if (isset($data['zunify'])) {
            $this->zunify = new Zunify();
            $this->zunify->setData($data['zunify']);
        }

        if (isset($data['mode'])) {
            $this->mode = $data['mode'];
        }

        if (isset($data['status'])) {
            $this->status = $data['status'];
        }

        if (isset($data['type'])) {
            $this->type = $data['type'];
        }

        if (isset($data['updatedAt'])) {
            $this->updatedAt = new DateTime($data['updatedAt']);
        }
    }

    public function toUpdateJson(): string
    {
        return json_encode($this->toUpdateArray());
    }

    public function toUpdateArray(): array
    {
        $data = [];

        // Solo incluir los campos que se pueden actualizar según el esquema
        if ($this->billing !== null) {
            $data['billing'] = [
                'address' => $this->billing->address->toArray(),
                'name' => $this->billing->name,
                'phone' => $this->billing->phone
            ];
        }

        // Solo incluir el objeto correspondiente al tipo de método de pago
        if ($this->type === 'card' && $this->card !== null && $this->card->cvv !== null) {
            $data['card'] = [
                'cvv' => $this->card->cvv
            ];
        }

        return $data;
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