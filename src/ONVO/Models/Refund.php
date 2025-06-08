<?php

namespace ONVO\Models;

use DateTime;

class Refund
{
    public ?string $id;
    public ?int $amount;
    public ?string $currency; // Enum: "USD" | "CRC"
    public ?DateTime $createdAt;
    public string $paymentIntentId;
    public ?string $description;
    public ?string $mode; // Enum: "test" | "live"
    public ?string $status; // Enum: "pending" | "succeeded" | "failed"
    public ?string $reason; // Enum: "requested_by_customer" | "fraudulent" | "duplicate"
    public ?DateTime $updatedAt;
    public ?string $failureReason;

    public function __construct(
        string $id,
        string $paymentIntentId,
        ?int $amount = null,
        ?string $currency = null,
        ?DateTime $createdAt = null,
        ?string $description = null,
        ?string $mode = null,
        ?string $status = null,
        ?string $reason = 'requested_by_customer',
        ?DateTime $updatedAt = null,
        ?string $failureReason = null
    ) {
        $this->id = $id;
        $this->paymentIntentId = $paymentIntentId;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->createdAt = $createdAt;
        $this->description = $description;
        $this->mode = $mode;
        $this->status = $status;
        $this->reason = $reason;
        $this->updatedAt = $updatedAt;
        $this->failureReason = $failureReason;
    }

    public function toArray($newRefund): array
    {
        if ($newRefund) {
            return [
                'amount' => $this->amount,
                'paymentIntentId' => $this->paymentIntentId,
                'description' => $this->description,
                'reason' => $this->reason,
            ];
        }

        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'createdAt' => $this->createdAt?->format(DateTime::ATOM),
            'paymentIntentId' => $this->paymentIntentId,
            'description' => $this->description,
            'mode' => $this->mode,
            'status' => $this->status,
            'reason' => $this->reason,
            'updatedAt' => $this->updatedAt?->format(DateTime::ATOM),
            'failureReason' => $this->failureReason,
        ];
    }

    public function toJson($newRefund): string
    {
        return json_encode($this->toArray($newRefund));
    }
}