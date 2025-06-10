<?php

namespace App\Models;

use DateTime;

class RecurringCharge
{
    public ?string $id;
    public ?DateTime $billingCycleAnchor;
    public ?string $status; // Enum: "trialing" etc.
    public ?DateTime $cancelAt;
    public bool $cancelAtPeriodEnd;
    public ?DateTime $canceledAt;
    public ?DateTime $createdAt;
    public ?DateTime $currentPeriodStart;
    public ?DateTime $currentPeriodEnd;
    public ?string $customerId;
    public ?string $description;
    public ?string $paymentBehavior;
    public ?DateTime $startDate;
    public ?string $paymentMethodId;
    public ?string $mode; // Enum: "test" | "live"
    public ?array $items;
    public ?int $trialPeriodDays;
    public ?DateTime $trialStart;
    public ?DateTime $trialEnd;
    public ?DateTime $updatedAt;
    public ?Invoice $latestInvoice;

    public function __construct(
        ?string $id = null,
        ?string $billingCycleAnchor = null,
        ?string $status = null,
        ?string $cancelAt = null,
        bool $cancelAtPeriodEnd = false,
        ?string $canceledAt = null,
        ?string $createdAt = null,
        ?string $currentPeriodStart = null,
        ?string $currentPeriodEnd = null,
        ?string $customerId = null,
        ?string $description = null,
        ?string $paymentBehavior = null,
        ?string $startDate = null,
        ?string $paymentMethodId = null,
        ?string $mode = null,
        ?array $items = null,
        ?int $trialPeriodDays = null,
        ?string $trialStart = null,
        ?string $trialEnd = null,
        ?string $updatedAt = null,
        ?Invoice $latestInvoice = null
    ) {
        $this->id = $id;

        $this->billingCycleAnchor = $billingCycleAnchor !== null ? new DateTime($billingCycleAnchor) : null;
        $this->status = $status;
        $this->cancelAt = $cancelAt !== null ? new DateTime($cancelAt) : null;
        $this->cancelAtPeriodEnd = $cancelAtPeriodEnd;
        $this->canceledAt = $canceledAt !== null ? new DateTime($canceledAt) : null;
        $this->createdAt = $createdAt !== null ? new DateTime($createdAt) : null;
        $this->currentPeriodStart = $currentPeriodStart !== null ? new DateTime($currentPeriodStart) : null;
        $this->currentPeriodEnd = $currentPeriodEnd !== null ? new DateTime($currentPeriodEnd) : null;
        $this->customerId = $customerId;
        $this->description = $description;
        $this->paymentBehavior = $paymentBehavior;
        $this->startDate = $startDate !== null ? new DateTime($startDate) : null;
        $this->paymentMethodId = $paymentMethodId;
        $this->mode = $mode;

        if ($items !== null) {
            $this->items = array_map(function ($i) {
                return new RecurringItem($i['priceId'], $i['quantity']);
            }, $items);
        } else {
            $this->items = null;
        }

        $this->trialPeriodDays = $trialPeriodDays;
        $this->trialStart = $trialStart !== null ? new DateTime($trialStart) : null;
        $this->trialEnd = $trialEnd !== null ? new DateTime($trialEnd) : null;
        $this->updatedAt = $updatedAt !== null ? new DateTime($updatedAt) : null;
        $this->latestInvoice = $latestInvoice !== null ? $latestInvoice : null;
    }

    public function setData(array $data)
    {
        if (isset($data['id'])) {
            $this->id = $data['id'];
        }
        if (isset($data['billingCycleAnchor'])) {
            $this->billingCycleAnchor = new DateTime($data['billingCycleAnchor']);
        }

        if (isset($data['status'])) {
            $this->status = $data['status'];
        }

        if (isset($data['cancelAt'])) {
            $this->cancelAt = new DateTime($data['cancelAt']);
        }

        if (isset($data['cancelAtPeriodEnd'])) {
            $this->cancelAtPeriodEnd = $data['cancelAtPeriodEnd'];
        }

        if (isset($data['canceledAt'])) {
            $this->canceledAt = new DateTime($data['canceledAt']);
        }

        if (isset($data['createdAt'])) {
            $this->createdAt = new DateTime($data['createdAt']);
        }

        if (isset($data['currentPeriodStart'])) {
            $this->currentPeriodStart = new DateTime($data['currentPeriodStart']);
        }

        if (isset($data['currentPeriodEnd'])) {
            $this->currentPeriodEnd = new DateTime($data['currentPeriodEnd']);
        }

        if (isset($data['customerId'])) {
            $this->customerId = $data['customerId'];
        }

        if (isset($data['description'])) {
            $this->description = $data['description'];
        }

        if (isset($data['paymentBehavior'])) {
            $this->paymentBehavior = $data['paymentBehavior'];
        }

        if (isset($data['startDate'])) {
            $this->startDate = new DateTime($data['']);
        }

        if (isset($data['paymentMethodId'])) {
            $this->paymentMethodId = $data['paymentMethodId'];
        }

        if (isset($data['mode'])) {
            $this->mode = $data['mode'];
        }

        if (isset($data['items'])) {
            $this->items = array_map(function ($i) {
                return new RecurringItem($i['priceId'], $i['quantity']);
            }, $data['items']);
        }

        if (isset($data['trialPeriodDays'])) {
            $this->trialPeriodDays = $data['trialPeriodDays'];
        }

        if (isset($data['trialStart'])) {
            $this->trialStart = new DateTime($data['trialStart']);
        }

        if (isset($data['trialEnd'])) {
            $this->trialEnd = new DateTime($data['trialEnd']);
        }
        
        if (isset($data['updatedAt'])) {
            $this->updatedAt = new DateTime($data['updatedAt']);
        }

        if (isset($data['latestInvoice'])) {
            $this->latestInvoice = new Invoice($data['latestInvoice']);
        }
    }

    public function toJson(bool $newCharge = false): string
    {
        return json_encode($this->toArray($newCharge));
    }

    public function toArray(bool $newCharge = false): array
    {
        if ($newCharge) {
            $data = [];
            if ($this->billingCycleAnchor) {
                $data['billingCycleAnchor'] = $this->billingCycleAnchor->format(DateTime::ATOM);
            }
            if ($this->cancelAt) {
                $data['cancelAt'] = $this->cancelAt->format(DateTime::ATOM);
            }
            $data['cancelAtPeriodEnd'] = $this->cancelAtPeriodEnd;
            $data['customerId'] = $this->customerId;
            if ($this->description) {
                $data['description'] = $this->description;
            }
            if ($this->paymentBehavior) {
                $data['paymentBehavior'] = $this->paymentBehavior;
            }
            if ($this->startDate) {
                $data['startDate'] = $this->startDate->format(DateTime::ATOM);
            }
            $data['paymentMethodId'] = $this->paymentMethodId;
            if ($this->items) {
                $data['items'] = array_map(fn($i) => $i->toArray(), $this->items);
            }
            if ($this->trialPeriodDays !== null) {
                $data['trialPeriodDays'] = $this->trialPeriodDays;
            }
            return $data;
        }
        return [
            'id' => $this->id,
            'billingCycleAnchor' => $this->billingCycleAnchor?->format(DateTime::ATOM),
            'status' => $this->status,
            'cancelAt' => $this->cancelAt?->format(DateTime::ATOM),
            'cancelAtPeriodEnd' => $this->cancelAtPeriodEnd,
            'canceledAt' => $this->canceledAt?->format(DateTime::ATOM),
            'createdAt' => $this->createdAt?->format(DateTime::ATOM),
            'currentPeriodStart' => $this->currentPeriodStart?->format(DateTime::ATOM),
            'currentPeriodEnd' => $this->currentPeriodEnd?->format(DateTime::ATOM),
            'customerId' => $this->customerId,
            'description' => $this->description,
            'paymentBehavior' => $this->paymentBehavior,
            'startDate' => $this->startDate?->format(DateTime::ATOM),
            'paymentMethodId' => $this->paymentMethodId,
            'mode' => $this->mode,
            'items' => $this->items ? array_map(fn($i) => $i->toArray(), $this->items) : null,
            'trialPeriodDays' => $this->trialPeriodDays,
            'trialStart' => $this->trialStart?->format(DateTime::ATOM),
            'trialEnd' => $this->trialEnd?->format(DateTime::ATOM),
            'updatedAt' => $this->updatedAt?->format(DateTime::ATOM),
            'latestInvoice' => $this->latestInvoice?->toArray(),
        ];
    }

    public function toUpdateArray(): array
    {
        $data = [];
        if ($this->billingCycleAnchor) {
            $data['billingCycleAnchor'] = $this->billingCycleAnchor->format(DateTime::ATOM);
        }
        if ($this->cancelAt) {
            $data['cancelAt'] = $this->cancelAt->format(DateTime::ATOM);
        }
        $data['cancelAtPeriodEnd'] = $this->cancelAtPeriodEnd;
        if ($this->customerId) {
            $data['customerId'] = $this->customerId;
        }
        if ($this->description) {
            $data['description'] = $this->description;
        }
        if ($this->paymentBehavior) {
            $data['paymentBehavior'] = $this->paymentBehavior;
        }
        if ($this->startDate) {
            $data['startDate'] = $this->startDate->format(DateTime::ATOM);
        }
        if ($this->paymentMethodId) {
            $data['paymentMethodId'] = $this->paymentMethodId;
        }
        if ($this->items) {
            $data['items'] = array_map(fn($i) => $i->toArray(), $this->items);
        }
        if ($this->trialPeriodDays !== null) {
            $data['trialPeriodDays'] = $this->trialPeriodDays;
        }
        return $data;
    }

    public function toUpdateJson(): string
    {
        return json_encode($this->toUpdateArray());
    }
}

class RecurringItem
{
    public ?string $id;
    public ?DateTime $createdAt;
    public string $priceId;
    public int $quantity;
    public ?DateTime $updatedAt;

    public function __construct(
        string $priceId,
        int $quantity,
    ) {
        $this->priceId = $priceId;
        $this->quantity = $quantity;
    }

    public function toJson(bool $newItem = false): string
    {
        return json_encode($this->toArray($newItem));
    }

    public function toArray(): array
    {
        return [
            'priceId' => $this->priceId,
            'quantity' => $this->quantity
        ];
    }
}

class Invoice
{
    public ?string $id;
    public ?string $mode;
    public ?string $currency;
    public ?int $attemptCount;
    public ?bool $attempted;
    public ?string $description;
    public ?int $total;
    public ?int $subtotal;
    public ?int $originalTotal;
    public ?string $status;
    public ?DateTime $createdAt;
    public ?DateTime $lastPaymentAttempt;
    public ?DateTime $nextPaymentAttempt;
    /** @var InvoiceAdditionalItem[]|null */
    public ?array $invoiceAdditionalItems;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? null;
        $this->mode = $data['mode'] ?? null;
        $this->currency = $data['currency'] ?? null;
        $this->attemptCount = $data['attemptCount'] ?? null;
        $this->attempted = $data['attempted'] ?? null;
        $this->description = $data['description'] ?? null;
        $this->total = $data['total'] ?? null;
        $this->subtotal = $data['subtotal'] ?? null;
        $this->originalTotal = $data['originalTotal'] ?? null;
        $this->status = $data['status'] ?? null;
        $this->createdAt = isset($data['createdAt']) ? new DateTime($data['createdAt']) : null;
        $this->lastPaymentAttempt = isset($data['lastPaymentAttempt']) ? new DateTime($data['lastPaymentAttempt']) : null;
        $this->nextPaymentAttempt = isset($data['nextPaymentAttempt']) ? new DateTime($data['nextPaymentAttempt']) : null;
        $this->invoiceAdditionalItems = isset($data['invoiceAdditionalItems']) ? array_map(
            fn($item) => new InvoiceAdditionalItem(
                $item['description'],
                $item['amount'],
                $item['quantity'],
                $item['currency'],
                $item['id'] ?? null,
                $item['mode'] ?? null,
                isset($item['updatedAt']) ? new DateTime($item['updatedAt']) : null,
                isset($item['createdAt']) ? new DateTime($item['createdAt']) : null
            ),
            $data['invoiceAdditionalItems']
        ) : null;
    }

    public function toArray(): array
    {
        $out = [
            'id' => $this->id,
            'mode' => $this->mode,
            'currency' => $this->currency,
            'attemptCount' => $this->attemptCount,
            'attempted' => $this->attempted,
            'description' => $this->description,
            'total' => $this->total,
            'subtotal' => $this->subtotal,
            'originalTotal' => $this->originalTotal,
            'status' => $this->status,
            'createdAt' => $this->createdAt?->format(DateTime::ATOM),
            'lastPaymentAttempt' => $this->lastPaymentAttempt?->format(DateTime::ATOM),
            'nextPaymentAttempt' => $this->nextPaymentAttempt?->format(DateTime::ATOM),
        ]; 
        if ($this->invoiceAdditionalItems) {
            $out['invoiceAdditionalItems'] = array_map(fn($i) => $i->toArray(), $this->invoiceAdditionalItems);
        }
        return $out;
    }
}

class InvoiceAdditionalItem
{
    public ?string $id;
    public ?string $mode;
    public ?string $description;
    public ?int $amount;
    public ?string $currency;
    public ?int $quantity;
    public ?DateTime $updatedAt;
    public ?DateTime $createdAt;

    public function __construct(
        string $description,
        int $amount,
        int $quantity,
        string $currency,
        ?string $id = null,
        ?string $mode = null,
        ?DateTime $updatedAt = null,
        ?DateTime $createdAt = null
    ) {
        $this->id = $id;
        $this->mode = $mode;
        $this->description = $description;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->quantity = $quantity;
        $this->updatedAt = $updatedAt;
        $this->createdAt = $createdAt;
    }

    public function toArray(bool $newItem = false): array
    {
        if ($newItem) {
            return [
                'description' => $this->description,
                'amount' => $this->amount,
                'quantity' => $this->quantity,
                'currency' => $this->currency,
            ];
        }

        $out = [
            'id' => $this->id,
            'mode' => $this->mode,
            'description' => $this->description,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'quantity' => $this->quantity,
            'updatedAt' => $this->updatedAt?->format(DateTime::ATOM),
            'createdAt' => $this->createdAt?->format(DateTime::ATOM),
        ];
        return $out;
    }

    public function toJson(bool $newItem = false): string
    {
        return json_encode($this->toArray($newItem));
    }

    public function toUpdateArray(): array
    {
        $data = [];
        if ($this->description !== null) {
            $data['description'] = $this->description;
        }
        if ($this->amount !== null) {
            $data['amount'] = $this->amount;
        }
        if ($this->quantity !== null) {
            $data['quantity'] = $this->quantity;
        }
        if ($this->currency !== null) {
            $data['currency'] = $this->currency;
        }
        return $data;
    }

    public function toUpdateJson(): string
    {
        return json_encode($this->toUpdateArray());
    }
}