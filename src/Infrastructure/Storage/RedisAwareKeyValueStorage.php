<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Infrastructure\Storage;

use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Domain\Storage\ConnectorException;
use Raketa\BackendTestTask\Domain\Storage\KeyValueStorageInterface;
use Redis;
use RedisException;

readonly class RedisAwareKeyValueStorage implements KeyValueStorageInterface
{
    public function __construct(
        private Redis           $redis,
        private LoggerInterface $logger,
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function get(string $key): string
    {
        try {
            return unserialize($this->getRedis()->get($key));
        } catch (RedisException $e) {
            $connectorException = new ConnectorException('Connector error', $e->getCode(), $e);
            $this->logger->error($connectorException->getMessage(), ['exception' => $e]);

            throw $connectorException;
        }
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, string $value): void
    {
        try {
            $this->getRedis()->setex($key, 24 * 60 * 60, $value);
        } catch (RedisException $e) {
            $connectorException = new ConnectorException('Connector error', $e->getCode(), $e);
            $this->logger->error($connectorException->getMessage(), ['exception' => $e]);

            throw $connectorException;
        }
    }

    /**
     * @throws ConnectorException
     */
    private function getRedis(): Redis
    {
        // Проверка, что соединение живое
        if ($this->redis->isConnected()) {
            return $this->redis;
        }

        // Триггерим reconnect по пингу
        if ($this->redis->ping('Pong')) {
            return $this->redis;
        }

        throw new ConnectorException('Redis connection lost');
    }
}
