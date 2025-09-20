<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Application\Http\View;

use Raketa\BackendTestTask\Domain\Model\Cart;
use Raketa\BackendTestTask\Domain\Model\CartItem;
use Raketa\BackendTestTask\Domain\Model\Product;
use Raketa\BackendTestTask\Domain\Repository\ProductRepositoryInterface;
use Ramsey\Uuid\UuidInterface;

readonly class CartView
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    )
    {
    }

    /**
     * @return array{
     *     uuid: string,
     *     customer: array{id: int, name: string, email: string},
     *     payment_method: string,
     *     items: array{uuid: string, price: float, total: float, quantity: int, product: array{uuid: string, name: string, thumbnail: string, price: float}}[],
     *     total: float
     * }
     */
    public function toArray(Cart $cart): array
    {
        $data = [
            'uuid' => $cart->getUuid(),
            'customer' => [
                'id' => $cart->getCustomer()->getId(),
                'name' => $cart->getCustomer()->getFullName(),
                'email' => $cart->getCustomer()->getEmail(),
            ],
            'payment_method' => $cart->getPaymentMethod(),
            'items' => [],
        ];

        $productsMap = $this->getProductsMap($cart);
        $totalOfCart = 0;

        foreach ($cart->getItems() as $item) {
            $totalOfRow = $item->getPrice() * $item->getQuantity();
            $totalOfCart += $totalOfRow;

            $product = $productsMap[$item->getProductUuid()->toString()];

            $data['items'][] = [
                'uuid' => $item->getUuid(),
                'price' => $item->getPrice(),
                'total' => $totalOfRow,
                'quantity' => $item->getQuantity(),
                'product' => [
                    'uuid' => $product->getUuid(),
                    'name' => $product->getName(),
                    'thumbnail' => $product->getThumbnail(),
                    'price' => $product->getPrice(),
                ],
            ];
        }

        $data['total'] = $totalOfCart;

        return $data;
    }

    /**
     * @return array<string, Product>
     */
    private function getProductsMap(Cart $cart): array
    {
        $products = $this->productRepository->getByUuids(
            array_map(static fn(CartItem $i): UuidInterface => $i->getProductUuid(), $cart->getItems()),
        );

        $productsMap = [];

        while ($product = array_shift($products)) {
            $productsMap[$product->getUuid()->toString()] = $product;
        }

        return $productsMap;
    }
}
