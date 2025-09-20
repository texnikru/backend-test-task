<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Infrastructure;

use Redis;
use RedisException;

readonly class RedisAwareStorage implements StorageInterface
{
    public function __construct(
        private Redis $redis,
    )
    {
    }

    /**
     * @throws ConnectorException
     */
    public function get(string $key): string
    {
        try {
            return unserialize($this->redis->get($key));
        } catch (RedisException $e) {
            throw new ConnectorException('Connector error', $e->getCode(), $e);
        }
    }

    /**
     * @throws ConnectorException
     */
    public function set(string $key, string $value): void
    {
        try {
            $this->redis->setex($key, 24 * 60 * 60, $value);
        } catch (RedisException $e) {
            throw new ConnectorException('Connector error', $e->getCode(), $e);
        }
    }
}
