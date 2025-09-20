<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Domain\Repository;

use Raketa\BackendTestTask\Domain\Model\Product;
use Ramsey\Uuid\UuidInterface;

interface ProductRepositoryInterface
{
    /**
     * @param UuidInterface[] $uuids
     * @return Product[]
     */
    public function getByUuids(array $uuids): array;

    /**
     * @return Product[]
     */
    public function getByCategory(string $category): array;
}
