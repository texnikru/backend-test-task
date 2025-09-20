<?php

namespace Raketa\BackendTestTask\View;

use Raketa\BackendTestTask\Repository\Entity\Product;
use Raketa\BackendTestTask\Repository\ProductRepository;

readonly class ProductsView
{
    public function __construct(
        private ProductRepository $productRepository
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
