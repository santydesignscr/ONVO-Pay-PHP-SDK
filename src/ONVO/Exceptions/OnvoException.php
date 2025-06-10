<?php

namespace ONVO\Exceptions;

use Exception;

class OnvoException extends \Exception
{
    protected int $statusCode;
    protected string $apiCode;
    protected array $messages;
    protected string $error;

    public function __construct(
        ?int $statusCode = null,
        ?string $apiCode = null,
        ?array $messages = null,
        ?string $error = null
    ) {
        $this->statusCode = $statusCode;
        $this->apiCode = $apiCode;
        $this->messages = $messages;
        $this->error = $error;

        // Se pasa un mensaje principal al constructor padre
        parent::__construct(implode(' | ', $messages), $statusCode);
    }

    public function setData(array $data)
    {
        if (isset($data['statusCode'])) {
            $this->statusCode = $data['statusCode'];
        }

        if (isset($data['apiCode'])) {
            $this->apiCode = $data['apiCode'];
        }

        if (isset($data['message'])) {
            $this->messages = $data['message'];
        }

        if (isset($data['error'])) {
            $this->error = $data['error'];
        }
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getApiCode(): string
    {
        return $this->apiCode;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function toArray(): array
    {
        return [
            'statusCode' => $this->statusCode,
            'apiCode'    => $this->apiCode,
            'message'    => $this->messages,
            'error'      => $this->error,
        ];
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }
}
