<?php

namespace App\Service;


class CacheService extends AbstractService
{
    /**
     * @return void
     */
    public function flushAll(): void
    {
        $this->getRedisClient()->flushAll();
    }

}
