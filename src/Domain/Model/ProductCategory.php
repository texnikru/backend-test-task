<?php

namespace Raketa\BackendTestTask\Domain\Model;

readonly class ProductCategory
{
    public function __construct(
        private int    $id,
        private string $name,
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
    }


    public function getName(): string
    {
        return $this->name;
    }

}