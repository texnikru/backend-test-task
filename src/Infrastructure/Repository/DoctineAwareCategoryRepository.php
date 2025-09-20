<?php

namespace Raketa\BackendTestTask\Infrastructure\Repository;

use Doctrine\DBAL\Connection as DbalConneciton;
use Raketa\BackendTestTask\Domain\Model\ProductCategory;
use Raketa\BackendTestTask\Domain\Repository\CategoryRepositoryInterface;

readonly class DoctineAwareCategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(
        private DbalConneciton $connection,
    )
    {
    }

    public function getByName(string $name): ProductCategory
    {
        // TODO: Implement getByName() method.
    }
}