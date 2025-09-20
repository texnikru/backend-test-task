<?php

namespace Raketa\BackendTestTask\Domain\Repository;

use Raketa\BackendTestTask\Domain\Model\ProductCategory;

interface CategoryRepositoryInterface
{
    public function getByName(string $name): ProductCategory;
}
