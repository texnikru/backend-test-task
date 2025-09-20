<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Infrastructure\Repository;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection as DbalConneciton;
use Raketa\BackendTestTask\Domain\Model\Product;
use Raketa\BackendTestTask\Domain\Model\ProductCategory;
use Raketa\BackendTestTask\Domain\Repository\ProductRepositoryInterface;
use Ramsey\Uuid\Lazy\LazyUuidFromString;
use Ramsey\Uuid\UuidInterface;

readonly class DoctrineAwareProductRepository implements ProductRepositoryInterface
{
    public function __construct(
        private DbalConneciton $connection,
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getByUuids(array $uuids): array
    {
        $rows = $this->connection->fetchAllAssociative(
            "SELECT id, uuid, is_active, category_name, name, description, thumbnail, price
                    FROM products
                    WHERE uuid IN (:uuids)",
            ['uuids' => array_map(static fn(UuidInterface $i): string => $i->toString(), $uuids)],
            ['uuids' => ArrayParameterType::STRING],
        );

        return array_map(
            static fn(array $row): Product => self::make($row),
            $rows,
        );
    }

    /**
     * @inheritDoc
     */
    public function getByCategory(ProductCategory $category): array
    {
        $rows = $this->connection->fetchAllAssociative(
            "SELECT id, uuid, is_active, category_name, name, description, thumbnail, price
                        FROM products
                        WHERE is_active = 1 AND category_id = :categoryId",
            ['categoryId' => $category->getId()],
        );

        return array_map(
            static fn(array $row): Product => self::make($row),
            $rows,
        );
    }

    private static function make(array $row): Product
    {
        return new Product(
            LazyUuidFromString::fromBytes($row['uuid']),
            (bool)$row['is_active'],
            $row['category_name'],
            $row['name'],
            $row['description'],
            $row['thumbnail'],
            (float)$row['price'],
        );
    }
}
