<?php
namespace ONVO\Models\Events;

abstract class BaseEvent
{
    protected string $type;
    protected array  $rawData;

    public function __construct(string $type, array $rawData)
    {
        $this->type    = $type;
        $this->rawData = $rawData;
        $this->validate();
    }

    /** Asegura que el payload tenga los campos requeridos y mapea a las propiedades */
    abstract protected function validate(): void;

    public function getType(): string
    {
        return $this->type;
    }

    public function getRawData(): array
    {
        return $this->rawData;
    }
}