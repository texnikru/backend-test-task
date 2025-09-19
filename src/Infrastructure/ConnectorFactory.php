<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Infrastructure;

use Redis;
use RedisException;

class ConnectorFactory
{
    public static function create(
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

        return new Connector($redis);
    }
}
