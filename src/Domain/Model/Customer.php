<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Domain\Model;

final readonly class Customer
{
    public function __construct(
        private int    $id,
        private string $firstName,
        private string $lastName,
        private string $middleName,
        private string $email,
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    private function getFirstName(): string
    {
        return $this->firstName;
    }

    private function getLastName(): string
    {
        return $this->lastName;
    }

    private function getMiddleName(): string
    {
        return $this->middleName;
    }

    public function getFullName(): string
    {
        return implode(' ', [
            $this->getLastName(),
            $this->getFirstName(),
            $this->getMiddleName(),
        ]);
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
