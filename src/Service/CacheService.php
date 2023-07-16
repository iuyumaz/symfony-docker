<?php

namespace App\Service;


class CacheService extends AbstractService
{
    /**
     * @return void
     * @throws \RedisException
     */
    public function flushAll(): void
    {
        $this->getRedisClient()->flushAll();
    }

}
