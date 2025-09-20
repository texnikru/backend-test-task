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

    // uuid идентификатор корзины
    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

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
