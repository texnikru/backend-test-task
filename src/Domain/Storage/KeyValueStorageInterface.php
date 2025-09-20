<?php

namespace Raketa\BackendTestTask\Domain\Storage;

interface KeyValueStorageInterface
{
    /**
     * @throws ConnectorException
     */
    public function get(string $key): string;

    /**
     * @throws ConnectorException
     */
    public function set(string $key, string $value, int $ttl): void;
}
