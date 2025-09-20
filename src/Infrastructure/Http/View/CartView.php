<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Infrastructure\Http\View;

use Raketa\BackendTestTask\Domain\Model\Cart;
use Raketa\BackendTestTask\Domain\Repository\ProductRepositoryInterface;

readonly class CartView
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {
    }

    public function toArray(Cart $cart): array
    {
        $data = [
            'uuid' => $cart->getUuid(),
            'customer' => [
                'id' => $cart->getCustomer()->getId(),
                'name' => implode(' ', [
                    $cart->getCustomer()->getLastName(),
                    $cart->getCustomer()->getFirstName(),
                    $cart->getCustomer()->getMiddleName(),
                ]),
                'email' => $cart->getCustomer()->getEmail(),
            ],
            'payment_method' => $cart->getPaymentMethod(),
        ];

        $total = 0;
        $data['items'] = []; // Можно пустой массив задать в структуре выше
        foreach ($cart->getItems() as $item) {
            $total += $item->getPrice() * $item->getQuantity();
            // Плохо ходить за товаром по-штучно. Нужно обращаться со списком товаров
            // А если товара нет? Но такого быть не может?!
            $product = $this->productRepository->getByUuid($item->getProductUuid());

            $data['items'][] = [
                'uuid' => $item->getUuid(),
                'price' => $item->getPrice(),
                'total' => $total, // Сумма строки путается с ценой всей корзины
                'quantity' => $item->getQuantity(),
                'product' => [
                    'uuid' => $product->getUuid(),
                    'name' => $product->getName(),
                    'thumbnail' => $product->getThumbnail(),
                    'price' => $product->getPrice(),
                ],
            ];
        }

        $data['total'] = $total;

        return $data;
    }
}
