<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Domain\Model;

use Ramsey\Uuid\UuidInterface;

final readonly class Cart
{
    public function __construct(
        private UuidInterface $uuid,
        private Customer      $customer,
        private string        $paymentMethod,
        private array         $items,
    )
    {
    }

    // Файл src/Repository/CartManager.php
    //
    //     public function getCart()
    //    {
    //        try {
    //            return $this->connector->get(session_id());
    //        } catch (Exception $e) {
    //            $this->logger->error('Error');
    //        }
    //
    //        return new Cart(session_id(), []);
    //    }
    //
    // Файл src/Domain/Model/Cart.php
    //
    //    final class Cart
    //    {
    //        public function __construct(
    //            readonly private string $uuid,
    //            readonly private Customer $customer,
    //            readonly private string $paymentMethod,
    //            private array $items,
    //        ) {
    //        }
    //
    // Выходит, что свойство Cart.uuid - это идентификатор сессии. Не нужно, корзина и так прибита к сессии.
    // Корзина не может владеть сессией, а наоборот.
    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    // Не корзина владеет покупателем, а покупатель корзиной. Этого быть тут не должно.
    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function addItem(CartItem $item): static
    {
        return new static(
            $this->uuid,
            $this->customer,
            $this->paymentMethod,
            array_merge($this->items, [$item]), // ключи не важны, просто мёржим
        );
    }
}
