<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Infrastructure\Storage;

use Redis;

class StorageFactory
{
    public static function buildRedisStorage(
        string $host,
        int    $port,
        string $password,
        int    $dbindex,
    )
    {
        $redis = new Redis();
        $isConnected = $redis->connect($host, $port); // @todo проверку

        $redis->auth($password);
        $redis->select($dbindex);

        return new RedisAwareStorage($redis);
    }
}
