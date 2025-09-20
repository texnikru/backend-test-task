<?php

namespace Raketa\BackendTestTask\Infrastructure\Http\View;

use Raketa\BackendTestTask\Domain\Repository\ProductRepositoryInterface;
use Raketa\BackendTestTask\Infrastructure\Repository\Entity\Product;

readonly class ProductsView
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {
    }

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
            // Дёргаем реп по-штучно, лучше один раз прийти со списком. Скорее-всего категорий мало.
            $this->productRepository->getByCategory($category)
        );
    }
}
