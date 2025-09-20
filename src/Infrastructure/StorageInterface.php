<?php

namespace Raketa\BackendTestTask\Infrastructure;

interface StorageInterface
{
    public function get(string $key): string;

    /**
     * @throws ConnectorException
     */
    public function set(string $key, string $value): void;
}
