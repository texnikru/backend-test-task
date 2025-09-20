<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Infrastructure\Repository\Entity;

use Ramsey\Uuid\UuidInterface;

readonly class Product
{
    public function __construct(
        private UuidInterface $uuid,
        private bool          $isActive,
        private string        $category,
        private string        $name,
        private string        $description,
        private string        $thumbnail,
        private float         $price,
    )
    {
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    // не используется
    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getThumbnail(): string
    {
        return $this->thumbnail;
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}
