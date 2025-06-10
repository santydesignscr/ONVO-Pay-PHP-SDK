<?php
// src/Exceptions/WebhookException.php

namespace ONVO\Exceptions;

/**
 * Excepción genérica para cualquier error en el flujo de webhooks:
 * - Payload inválido
 * - Handler ausente
 * - Cualquier otro fallo interno
 */
class WebhookException extends \Exception
{
    
}