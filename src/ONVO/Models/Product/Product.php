<?php

namespace ONVO\Models\Product;

use DateTime;

class Product
{
    public ?string $id;
    public ?DateTime $createdAt;
    public ?string $description;
    public ?array $images;
    public bool $isActive;
    public bool $isShippable;
    public string $name;
    public ?PackageDimensions $packageDimensions;
    public string $mode; // Enum: "test" | "live"
    public ?DateTime $updatedAt;

    public function __construct(
        string $name,
        bool $isActive,
        bool $isShippable,
        ?array $images = null,
        ?string $description = null,
        ?PackageDimensions $packageDimensions = null,
        ?string $id = null,
        ?DateTime $createdAt = null,
        string $mode = 'test',
        ?DateTime $updatedAt = null
    ) {
        $this->id = $id;
        $this->createdAt = $createdAt;
        $this->description = $description;
        $this->images = $images;
        $this->isActive = $isActive;
        $this->isShippable = $isShippable;
        $this->name = $name;
        $this->packageDimensions = $packageDimensions;
        $this->mode = $mode;
        $this->updatedAt = $updatedAt;
    }

    public function toJson(bool $newProduct = false): string
    {
        return json_encode($this->toArray($newProduct));
    }

    public function toArray(bool $newProduct = false): array
    {
        if ($newProduct) {
            $data = [
                'name' => $this->name,
                'isActive' => $this->isActive,
                'isShippable' => $this->isShippable,
            ];
            if ($this->description !== null) {
                $data['description'] = $this->description;
            }
            if ($this->images !== null) {
                $data['images'] = $this->images;
            }
            if ($this->packageDimensions !== null) {
                $data['packageDimensions'] = $this->packageDimensions->toArray();
            }
            return $data;
        }

        return [
            'id' => $this->id,
            'createdAt' => $this->createdAt?->format(DateTime::ATOM),
            'description' => $this->description,
            'images' => $this->images,
            'isActive' => $this->isActive,
            'isShippable' => $this->isShippable,
            'name' => $this->name,
            'packageDimensions' => $this->packageDimensions?->toArray(),
            'mode' => $this->mode,
            'updatedAt' => $this->updatedAt?->format(DateTime::ATOM),
        ];
    }

    public function toUpdateArray(): array
    {
        $data = [];
        if ($this->name !== null) {
            $data['name'] = $this->name;
        }
        if (isset($this->isActive)) {
            $data['isActive'] = $this->isActive;
        }
        if (isset($this->isShippable)) {
            $data['isShippable'] = $this->isShippable;
        }
        if ($this->description !== null) {
            $data['description'] = $this->description;
        }
        if ($this->images !== null) {
            $data['images'] = $this->images;
        }
        if ($this->packageDimensions !== null) {
            $data['packageDimensions'] = $this->packageDimensions->toArray();
        }
        return $data;
    }

    public function toUpdateJson(): string
    {
        return json_encode($this->toUpdateArray());
    }
}

class PackageDimensions
{
    public float $length;
    public float $width;
    public float $height;
    public float $weight; // en gramos

    public function __construct(float $length, float $width, float $height, float $weight)
    {
        $this->length = $length;
        $this->width = $width;
        $this->height = $height;
        $this->weight = $weight;
    }

    public function toArray(): array
    {
        return [
            'length' => $this->length,
            'width' => $this->width,
            'height' => $this->height,
            'weight' => $this->weight,
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }
}