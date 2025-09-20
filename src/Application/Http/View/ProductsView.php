<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Application\Http\View;

use Raketa\BackendTestTask\Domain\Model\Product;
use Raketa\BackendTestTask\Domain\Repository\ProductRepositoryInterface;

readonly class ProductsView
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {
    }

    /**
     * @param string $category
     * @return array{uuid: string, category: string, description: string, thumbnail: string, price: float}[]
     */
    public function toArray(string $category): array
    {
        return array_map(
            fn (Product $product) => [
                'uuid' => $product->getUuid(),
                'category' => $product->getCategory(),
                'description' => $product->getDescription(),
                'thumbnail' => $product->getThumbnail(),
                'price' => $product->getPrice(),
            ],
            $this->productRepository->getByCategory($category)
        );
    }
}
