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
        ?string $name = null,
        ?bool $isActive = null,
        ?bool $isShippable = null,
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

    public function setData(array $data)
    {
        if (isset($data['id'])) {
            $this->id = $data['id'];
        }
        if (isset($data['createdAt'])) {
            $this->createdAt = new DateTime($data['createdAt']);
        }
        if (isset($data['description'])) {
            $this->description = $data['description'];
        }
        if (isset($data['images'])) {
            $this->images = $data['images'];
        }
        if (isset($data['isActive'])) {
            $this->isActive = $data['isActive'];
        }
        if (isset($data['isShippable'])) {
            $this->isShippable = $data['isShippable'];
        }
        if (isset($data['name'])) {
            $this->name = $data['name'];
        }
        if (isset($data['packageDimensions'])) {
            $this->packageDimensions = new PackageDimensions();
            $this->packageDimensions->setData($data['packageDimensions']);
        }
        if (isset($data['mode'])) {
            $this->mode = $data['mode'];
        }
        if (isset($data['updatedAt'])) {
            $this->updatedAt = new DateTime($data['updatedAt']);
        }
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

    public function __construct(
        ?float $length = null,
        ?float $width = null,
        ?float $height = null,
        ?float $weight = null
    )
    {
        $this->length = $length;
        $this->width = $width;
        $this->height = $height;
        $this->weight = $weight;
    }

    public function setData(array $data)
    {
        if (isset($data['length'])) {
            $this->length = $data['length'];
        }

        if (isset($data['width'])) {
            $this->width = $data['width'];
        }

        if (isset($data['height'])) {
            $this->height = $data['height'];
        }

        if (isset($data['weight'])) {
            $this->weight = $data['weight'];
        }
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