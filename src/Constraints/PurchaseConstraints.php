<?php

namespace App\Constraints;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AuthConstraints
 * @package Admin\Service\Validation
 */
class PurchaseConstraints
{
    /**
     * @return Assert\Collection
     */
    public static function verify(): Assert\Collection
    {
        return new Assert\Collection([
            'clientToken' => new Assert\Required([
                new Assert\NotBlank()
            ]),
            'receipt' => new Assert\Required([
                new Assert\NotBlank()
            ])
        ]);
    }

}
