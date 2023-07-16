<?php

namespace App\Exception;

class ValidationException extends \Exception
{

    /**
     * @param $message
     * @param $code
     * @param \Exception|null $previous
     * @param array $errors
     */
    public function __construct($message = "", $code = 0, \Exception $previous = null, protected array $errors = [])
    {
        parent::__construct($message, $code, $previous);
        $this->setErrors($errors);
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     */
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }

}
