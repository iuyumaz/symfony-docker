<?php

namespace App\Constants;

class SubscriptionConstants
{
    const STATUS_STARTED = 'started';

    const STATUS_CANCELED = 'canceled';

    const STATUS_RENEWED = 'renewed';

    /** @var string[] */
    public static array $getValidStatusList = [
        self::STATUS_STARTED,
        self::STATUS_CANCELED,
        self::STATUS_RENEWED
    ];

}
