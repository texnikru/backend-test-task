<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Domain\Model;

use Ramsey\Uuid\UuidInterface;

readonly class Product
{
    public function __construct(
        private UuidInterface $uuid,
        private bool          $isActive,
        private string        $categoryName,
        private string        $name,
        private ?string       $description,
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

    public function getCategoryName(): string
    {
        return $this->categoryName;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
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
