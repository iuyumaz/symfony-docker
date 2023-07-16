<?php

namespace App\Constraints;

use App\Constants\SubscriptionConstants;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AuthConstraints
 * @package Admin\Service\Validation
 */
class SubscriptionConstraints
{
    /**
     * @return Assert\Collection
     */
    public static function verifyCheck(): Assert\Collection
    {
        return new Assert\Collection([
            'clientToken' => new Assert\Required([
                new Assert\NotBlank()
            ])
        ]);
    }

    /**
     * @return Assert\Collection
     */
    public static function verifyUpdate(): Assert\Collection
    {
        return new Assert\Collection([
            'status' => new Assert\Required([
                new Assert\NotBlank(),
                new Assert\Choice(
                    SubscriptionConstants::$getValidStatusList
                )
            ])
        ]);
    }

}
