<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Infrastructure\Storage;

use Raketa\BackendTestTask\Domain\Storage\ConnectorException;
use Redis;
use RedisException;
use RuntimeException;

class ExternalStorageFactory
{
    /**
     * @throws ConnectorException
     */
    public static function buildRedisStorage(
        string $host,
        int    $port,
        string $password,
        int    $dbIndex,
    )
    {
        $redis = new Redis();

        // Контракты говорят, что возвращает булево false без бросков исключений
        // Конструкцию try-catch оставил из-за недостатка документации - если исключений методы не бросают,
        // то почему класс исключения RedisException существует?
        try {
            $isOk = $redis->connect($host, $port);
            $isOk = $isOk && false !== $redis->auth($password);
            $isOk = $isOk && false !== $redis->select($dbIndex);
        } catch (RedisException $e) {
            throw new ConnectorException('Connector error: ' . $e->getMessage(), $e->getCode(), $e);
        }

        if (!$isOk) {
            throw new RuntimeException('Redis connection not established');
        }

        return new RedisAwareKeyValueStorage($redis);
    }
}
