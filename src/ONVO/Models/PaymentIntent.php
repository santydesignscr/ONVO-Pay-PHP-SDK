<?php

namespace ONVO\Models;

use DateTime;

class PaymentIntent
{
    public ?string $id;
    public ?int $amount;
    public ?int $baseAmount;
    public ?float $exchangeRate;
    public ?int $capturableAmount;
    public ?int $receivedAmount;
    public ?string $captureMethod; // Enum: "manual" | "automatic"
    public ?string $currency; // Enum: "USD" | "CRC"
    public ?DateTime $createdAt;
    public ?string $customerId;
    public ?string $description;
    /** @var Charge[]|null */
    public ?array $charges;
    public ?PaymentError $lastPaymentError;
    public ?string $mode; // Enum: "test" | "live"
    public ?string $status; // various states
    public ?DateTime $updatedAt;
    public ?array $metadata;
    public ?string $officeId;
    public ?string $onBehalfOf;
    public ?NextAction $nextAction;

    public function __construct(
        ?string $id = null,
        ?int $amount = null,
        ?int $baseAmount = null,
        ?float $exchangeRate = null,
        ?int $capturableAmount = null,
        ?int $receivedAmount = null,
        ?string $captureMethod = null,
        ?string $currency = null,
        ?DateTime $createdAt = null,
        ?string $customerId = null,
        ?string $description = null,
        ?array $charges = null,
        ?PaymentError $lastPaymentError = null,
        ?string $mode = null,
        ?string $status = null,
        ?DateTime $updatedAt = null,
        ?array $metadata = null,
        ?string $officeId = null,
        ?string $onBehalfOf = null,
        ?NextAction $nextAction = null
    ) {
        $this->id = $id;
        $this->amount = $amount;
        $this->baseAmount = $baseAmount;
        $this->exchangeRate = $exchangeRate;
        $this->capturableAmount = $capturableAmount;
        $this->receivedAmount = $receivedAmount;
        $this->captureMethod = $captureMethod;
        $this->currency = $currency;
        $this->createdAt = $createdAt;
        $this->customerId = $customerId;
        $this->description = $description;
        $this->charges = $charges;
        $this->lastPaymentError = $lastPaymentError;
        $this->mode = $mode;
        $this->status = $status;
        $this->updatedAt = $updatedAt;
        $this->metadata = $metadata;
        $this->officeId = $officeId;
        $this->onBehalfOf = $onBehalfOf;
        $this->nextAction = $nextAction;
    }

    public function toJson(bool $newIntent = false): string
    {
        return json_encode($this->toArray($newIntent));
    }

    public function toArray(bool $newIntent = false): array
    {
        if ($newIntent) {
            return [
                'amount' => $this->amount,
                'captureMethod' => $this->captureMethod,
                'currency' => $this->currency,
                'customerId' => $this->customerId,
                'description' => $this->description,
                'metadata' => $this->metadata,
                'officeId' => $this->officeId,
                'onBehalfOf' => $this->onBehalfOf,
            ];
        }

        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'baseAmount' => $this->baseAmount,
            'exchangeRate' => $this->exchangeRate,
            'capturableAmount' => $this->capturableAmount,
            'receivedAmount' => $this->receivedAmount,
            'captureMethod' => $this->captureMethod,
            'currency' => $this->currency,
            'createdAt' => $this->createdAt?->format(DateTime::ATOM),
            'customerId' => $this->customerId,
            'description' => $this->description,
            'charges' => $this->charges !== null ? array_map(fn($c) => $c->toArray(), $this->charges) : null,
            'lastPaymentError' => $this->lastPaymentError?->toArray(),
            'mode' => $this->mode,
            'status' => $this->status,
            'updatedAt' => $this->updatedAt?->format(DateTime::ATOM),
            'metadata' => $this->metadata,
            'officeId' => $this->officeId,
            'onBehalfOf' => $this->onBehalfOf,
            'nextAction' => $this->nextAction?->toArray(),
        ];
    }

    public function toUpdateArray(): array
    {
        $data = [];
        if ($this->amount !== null) {
            $data['amount'] = $this->amount;
        }
        if ($this->captureMethod !== null) {
            $data['captureMethod'] = $this->captureMethod;
        }
        if ($this->currency !== null) {
            $data['currency'] = $this->currency;
        }
        if ($this->customerId !== null) {
            $data['customerId'] = $this->customerId;
        }
        if ($this->description !== null) {
            $data['description'] = $this->description;
        }
        if ($this->metadata !== null) {
            $data['metadata'] = $this->metadata;
        }
        if ($this->officeId !== null) {
            $data['officeId'] = $this->officeId;
        }
        if ($this->onBehalfOf !== null) {
            $data['onBehalfOf'] = $this->onBehalfOf;
        }
        return $data;
    }

    public function toUpdateJson(): string
    {
        return json_encode($this->toUpdateArray());
    }
}

class Charge
{
    public ?string $id;
    public ?int $amount;
    public ?string $currency;
    public ?string $status;
    public ?DateTime $createdAt;

    public function __construct(
        ?string $id = null,
        ?int $amount = null,
        ?string $currency = null,
        ?string $status = null,
        ?DateTime $createdAt = null
    ) {
        $this->id = $id;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->status = $status;
        $this->createdAt = $createdAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'status' => $this->status,
            'createdAt' => $this->createdAt?->format(DateTime::ATOM),
        ];
    }
}

class PaymentError
{
    public ?string $code;
    public ?string $message;
    public ?string $type;

    public function __construct(
        ?string $code = null,
        ?string $message = null,
        ?string $type = null
    ) {
        $this->code = $code;
        $this->message = $message;
        $this->type = $type;
    }

    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'message' => $this->message,
            'type' => $this->type,
        ];
    }
}

class RedirectToUrl
{
    public ?string $url;
    public ?string $returnUrl;

    public function __construct(
        ?string $url = null,
        ?string $returnUrl = null
    ) {
        $this->url = $url;
        $this->returnUrl = $returnUrl;
    }

    public function toArray(): array
    {
        return [
            'url' => $this->url,
            'returnUrl' => $this->returnUrl,
        ];
    }
}

class NextAction
{
    public ?string $type;
    public ?RedirectToUrl $redirectToUrl;

    public function __construct(
        ?string $type = null,
        ?RedirectToUrl $redirectToUrl = null
    ) {
        $this->type = $type;
        $this->redirectToUrl = $redirectToUrl;
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'redirectToUrl' => $this->redirectToUrl?->toArray(),
        ];
    }
}