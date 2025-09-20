<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Domain\Model;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final readonly class CartItem
{
    public function __construct(
        private UuidInterface $uuid,
        private UuidInterface $productUuid,
        private float         $price,
        private int           $quantity,
    ) {
    }

    public function getUuid(): UuidInterface // методы не используются, допустимо в задании?
    {
        return $this->uuid;
    }

    public function getProductUuid(): UuidInterface
    {
        return $this->productUuid;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public static function ofProduct(Product $product, int $quantity): static
    {
        return new static(
            Uuid::uuid4(),
            $product->getUuid(),
            $product->getPrice(),
            $quantity,
        );
    }
}
