<?php

namespace App\Service\Validation;

use App\Exception\ValidationException;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidationService
{

    public function __construct(protected ValidatorInterface $validator, protected PropertyAccessorInterface $propertyAccessor)
    {
        $this->setValidator($validator);
        $this->setPropertyAccessor($propertyAccessor);
    }

    /**
     * @return ValidatorInterface
     */
    public function getValidator(): ValidatorInterface
    {
        return $this->validator;
    }

    /**
     * @param ValidatorInterface $validator
     */
    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @return PropertyAccessorInterface
     */
    public function getPropertyAccessor(): PropertyAccessorInterface
    {
        return $this->propertyAccessor;
    }

    /**
     * @param PropertyAccessorInterface $propertyAccessor
     */
    public function setPropertyAccessor(PropertyAccessorInterface $propertyAccessor)
    {
        $this->propertyAccessor = $propertyAccessor;
    }


    /**
     * @param $value
     * @param $constraints
     * @return array
     */
    public function validate($value, $constraints = null): array
    {
        $violations = $this->getValidator()->validate($value, $constraints);
        return $this->getViolationErrors($violations);
    }

    /**
     * @param $violations
     * @return array
     */
    protected function getViolationErrors($violations)
    {
        $errors = [];
        foreach ($violations as $violation) {
            /** @var ConstraintViolation $violation */
            try {
                $this->getPropertyAccessor()->setValue($errors, $violation->getPropertyPath(), $violation->getMessage());
            } catch (NoSuchPropertyException $e) {
                $this->getPropertyAccessor()->setValue($errors, $this->getViolationPropertyPath($violation->getPropertyPath()), $violation->getMessage());
            }
        }
        return $errors;
    }

    /**
     * @param $propertyPath
     * @return string
     */
    protected function getViolationPropertyPath($propertyPath)
    {
        $propertyPath = str_replace(["[", "]"], [".", ""], $propertyPath);
        $propertyPathParams = explode(".", $propertyPath);
        $newPropertyPath = "";
        foreach ($propertyPathParams as $param) {
            $newPropertyPath .= '[' . $param . ']';
        }
        return $newPropertyPath;
    }

}
