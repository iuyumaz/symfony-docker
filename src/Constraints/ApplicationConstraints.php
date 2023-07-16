<?php

namespace App\Constraints;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AuthConstraints
 * @package Admin\Service\Validation
 */
class ApplicationConstraints
{
    /**
     * @return Assert\Collection
     */
    public static function verify(): Assert\Collection
    {
        return new Assert\Collection([
            'name' => new Assert\Required([
                new Assert\NotBlank()
            ]),
            'callbackUrl' => new Assert\Required([
                new Assert\NotBlank()
            ])
        ]);
    }

}
