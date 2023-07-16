<?php

namespace App\Constraints;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class AuthConstraints
 * @package Admin\Service\Validation
 */
class DeviceConstraints
{
    /**
     * @return Assert\Collection
     */
    public static function verify(): Assert\Collection
    {
        return new Assert\Collection([
            'uid' => new Assert\Required([
                new Assert\NotBlank()
            ]),
            'application' => new Assert\Required([
                new Assert\NotBlank(),
                new Assert\Callback(function ($value, ExecutionContextInterface $context) {
                    if (!isset($value['id'])) {
                        $context->buildViolation('Required "id" missing.')
                            ->addViolation();
                    }
                    if (isset($value['id']) && $value['id'] <= 0) {
                        $context->buildViolation('Id must be greater than 0.')
                            ->addViolation();
                    }
                })
            ]),
            'language' => new Assert\Required([
                new Assert\NotBlank()
            ]),
            'operatingSystem' => new Assert\Required([
                new Assert\NotBlank()
            ])
        ]);
    }

}
