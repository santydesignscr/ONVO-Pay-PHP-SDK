<?php
// src/Services/WebhookService.php

namespace ONVO\Services;

use ONVO\Models\Events\BaseEvent;
use ONVO\Exceptions\WebhookException;

class WebhookService
{
    private array $map = [
        'payment-intent.succeeded'       => \ONVO\Models\Events\PaymentIntentSucceededEvent::class,
        'payment-intent.failed'          => \ONVO\Models\Events\PaymentIntentFailedEvent::class,
        'payment-intent.deferred'        => \ONVO\Models\Events\PaymentIntentDeferredEvent::class,
        'subscription.renewal.succeeded'  => \ONVO\Models\Events\SubscriptionRenewalSucceededEvent::class,
        'subscription.renewal.failed'     => \ONVO\Models\Events\SubscriptionRenewalFailedEvent::class,
        'checkout-session.succeeded'      => \ONVO\Models\Events\CheckoutSessionSucceededEvent::class,
        'mobile-transfer.received'        => \ONVO\Models\Events\MobileTransferReceivedEvent::class,
    ];

    /**
     * Procesa el payload del webhook y retorna un array listo para JSON:
     * [
     *   'type' => string,
     *   'data' => array
     * ]
     *
     * @param  array<string,mixed> $payload
     * @return array<string,mixed>
     * @throws WebhookException
     */
    public function handle(array $payload): array
    {
        if (empty($payload['type']) || !isset($payload['data']) || !is_array($payload['data'])) {
            throw new WebhookException('Payload inválido: se requiere `type` y `data`.');
        }

        $type = $payload['type'];
        if (!isset($this->map[$type])) {
            throw new WebhookException("Evento desconocido: {$type}");
        }

        $class = $this->map[$type];
        if (!class_exists($class)) {
            throw new WebhookException("Clase de evento no encontrada: {$class}");
        }

        /** @var BaseEvent $event */
        $event = new $class($type, $payload['data']);

        return [
            'type' => $event->getType(),
            'data' => $event->getRawData(),
        ];
    }

    public function arrayToJson(array $array): string
    {
        return json_encode($array, JSON_PRETTY_PRINT);
    }
}