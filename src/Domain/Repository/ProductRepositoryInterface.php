<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Domain\Repository;

use Raketa\BackendTestTask\Infrastructure\Repository\Entity\Product;
use Ramsey\Uuid\UuidInterface;

interface ProductRepositoryInterface
{
    public function getByUuid(UuidInterface $uuid): ?Product;

    /**
     * @return Product[]
     */
    public function getByCategory(string $category): array;
}
