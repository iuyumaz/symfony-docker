<?php

namespace App\Client\Redis;

use Symfony\Component\Cache\Adapter\RedisAdapter;

class RedisClient
{

    public static function getRedisClient()
    {
        return RedisAdapter::createConnection('redis://redis:6379', [
            'class' => null,
            'persistent' => 0,
            'persistent_id' => null,
            'timeout' => 30,
            'read_timeout' => 0,
            'retry_interval' => 0,
            'tcp_keepalive' => 0,
            'lazy' => null,
            'redis_cluster' => false,
            'redis_sentinel' => null,
            'dbindex' => 0,
            'failover' => 'none',
            'ssl' => null,
        ]);
    }

}
