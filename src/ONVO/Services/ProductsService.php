<?php

namespace ONVO\Services;

use ONVO\Http\Client;
use ONVO\Models\Product\Product;
use ONVO\Models\Product\PackageDimensions;

class ProductsService
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Create a new product
     *
     * @param array $data Product creation parameters
     * @return Product
     */
    public function create(array $data): Product
    {
        $response = $this->client->post('/products', $data);
        
        return $this->mapResponseToProduct($response);
    }

    /**
     * List products with optional filtering
     *
     * @param array|null $params Optional parameters for filtering
     * @return array Array containing list of products and pagination metadata
     */
    public function list(?array $params = []): array
    {
        $response = $this->client->get('/products', $params, true);
        
        $products = [];
        foreach ($response['data'] as $productData) {
            $products[] = $this->mapResponseToProduct($productData);
        }
        
        return [
            'data' => $products,
            'hasMore' => $response['hasMore'] ?? false,
            'totalCount' => $response['totalCount'] ?? count($products),
        ];
    }

    /**
     * Retrieve a product by ID
     *
     * @param string $id Product ID
     * @return Product
     */
    public function retrieve(string $id): Product
    {
        $response = $this->client->get("/products/{$id}");
        
        return $this->mapResponseToProduct($response);
    }

    /**
     * Update a product
     *
     * @param string $id Product ID
     * @param array $data Product update parameters
     * @return Product
     */
    public function update(string $id, array $data): Product
    {
        $response = $this->client->post("/products/{$id}", $data);
        
        return $this->mapResponseToProduct($response);
    }

    /**
     * Delete a product
     *
     * @param string $id Product ID
     * @return array Deleted product response
     */
    public function delete(string $id): array
    {
        return $this->client->delete("/products/{$id}");
    }

    /**
     * Map API response to Product object
     *
     * @param array $data Response data
     * @return Product
     */
    private function mapResponseToProduct(array $data): Product
    {
        // Convertir fechas si existen
        $createdAt = isset($data['createdAt']) ? new \DateTime($data['createdAt']) : null;
        $updatedAt = isset($data['updatedAt']) ? new \DateTime($data['updatedAt']) : null;

        // Crear objetos relacionados
        $packageDimensions = null;
        if (isset($data['packageDimensions'])) {
            $packageDimensions = new PackageDimensions(
                $data['packageDimensions']['length'] ?? 0,
                $data['packageDimensions']['width'] ?? 0,
                $data['packageDimensions']['height'] ?? 0,
                $data['packageDimensions']['weight'] ?? 0
            );
        }

        // Crear y retornar el objeto Product
        return new Product(
            $data['name'] ?? '',
            $data['isActive'] ?? true,
            $data['isShippable'] ?? false,
            $data['images'] ?? null,
            $data['description'] ?? null,
            $packageDimensions,
            $data['id'] ?? null,
            $createdAt,
            $data['mode'] ?? 'test',
            $updatedAt
        );
    }
}