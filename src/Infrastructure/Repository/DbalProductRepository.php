<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Infrastructure\Repository;

use Doctrine\DBAL\Connection;
use Raketa\BackendTestTask\Domain\Model\Product;
use Raketa\BackendTestTask\Domain\Repository\ProductRepositoryInterface;
use Ramsey\Uuid\Lazy\LazyUuidFromString;
use Ramsey\Uuid\UuidInterface;

readonly class DbalProductRepository implements ProductRepositoryInterface
{
    public function __construct(
        private Connection $connection,
    )
    {
    }

    public function getByUuid(UuidInterface $uuid): ?Product
    {
        $row = $this->connection->fetchOne(
            "SELECT id, uuid, is_active, category, name, description, thumbnail, price
                    FROM products
                    WHERE uuid = :uuid",
            ['uuid' => $uuid]
        );

        if (empty($row)) {
            return null;
        }

        return self::make($row);
    }

    public function getByCategory(string $category): array
    {
        return array_map(
            static fn (array $row): Product => self::make($row),
            $this->connection->fetchAllAssociative(
                // Тут перечислить нужно колонки, одного id будет мало
                "SELECT id, uuid, is_active, category, name, description, thumbnail, price
                        FROM products
                        WHERE is_active = 1 AND category = :category",
                ['category' => $category]
            )
        );
    }

    private static function make(array $row): Product
    {
        return new Product(
            LazyUuidFromString::fromBytes($row['uuid']),
            (bool)$row['is_active'],
            $row['category'],
            $row['name'],
            $row['description'],
            $row['thumbnail'],
            (float)$row['price'],
        );
    }
}
