<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Infrastructure;

use Raketa\BackendTestTask\Domain\Cart;
use Redis;
use RedisException;

readonly class Connector
{
    public function __construct(
        private Redis $redis,
    )
    {
    }

    /**
     * @throws ConnectorException
     */
    public function get(Cart $key)
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
    public function set(string $key, Cart $value)
    {
        try {
            $this->redis->setex($key, 24 * 60 * 60, serialize($value));
        } catch (RedisException $e) {
            throw new ConnectorException('Connector error', $e->getCode(), $e);
        }
    }

    // Метод не используется. Обработка ошибок не нужна?
    public function has($key): bool
    {
        return $this->redis->exists($key);
    }
}
