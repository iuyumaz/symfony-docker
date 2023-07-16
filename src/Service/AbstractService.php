<?php

namespace App\Service;

use App\Client\Redis\RedisClient;

class AbstractService
{
    /**
     * @codeCoverageIgnore
     */
    protected function getRedisClient()
    {
        return RedisClient::getRedisClient();
    }

}
