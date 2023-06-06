<?php

namespace App\Exception;

use App\Error\Validation\ValidationErrorsList;
use Exception;

class ValidationException extends Exception
{
    public function __construct(private readonly ValidationErrorsList $errors)
    {
        parent::__construct('Validation error', 400);
    }

    public function errors(): ValidationErrorsList
    {
        return $this->errors;
    }
}
