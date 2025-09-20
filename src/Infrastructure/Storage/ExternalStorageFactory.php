<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Infrastructure\Storage;

use Redis;

class ExternalStorageFactory
{
    public static function buildRedisStorage(
        string $host,
        int    $port,
        string $password,
        int    $dbIndex,
    )
    {
        $redis = new Redis();
        $isConnected = $redis->connect($host, $port); // @todo проверку

        $redis->auth($password);
        $redis->select($dbIndex);

        return new RedisAwareKeyValueStorage($redis);
    }
}
